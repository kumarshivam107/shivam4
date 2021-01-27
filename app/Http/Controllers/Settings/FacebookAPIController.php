<?php

namespace App\Http\Controllers\Settings;

use App\FacebookAccount;
use App\FbGroup;
use App\FbPage;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Jackiedo\DotenvEditor\Exceptions\KeyNotFoundException;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //Check if facebook api env key exists...
        if(!DotenvEditor::keyExists('FACEBOOK_APP_ID')){
            DotenvEditor::addEmpty();
            DotenvEditor::setKey('FACEBOOK_APP_ID');
        }
        if(!DotenvEditor::keyExists('FACEBOOK_APP_SECRET')){
            DotenvEditor::setKey('FACEBOOK_APP_SECRET');
        }

        //Check current status of the facebook api, if exists...
        try {
            if (!empty(DotenvEditor::getValue('FACEBOOK_APP_ID')) && !empty(DotenvEditor::getValue('FACEBOOK_APP_SECRET'))){
                $client = new Client();
                $client->get('https://graph.facebook.com/oauth/access_token?client_id='.DotenvEditor::getValue('FACEBOOK_APP_ID').'&client_secret='.DotenvEditor::getValue('FACEBOOK_APP_SECRET').'&grant_type=client_credentials');

                $api_status = 'Active';
            }
        }catch(KeyNotFoundException $e){
            DotenvEditor::setKey('FACEBOOK_APP_ID');
            DotenvEditor::setKey('FACEBOOK_APP_SECRET');
        }catch(ClientException $e){
            $api_status = 'Inactive';
        }

        //Save buffer to file...
        DotenvEditor::save();
        return view('settings.facebook-api', compact('api_status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fb_app_id' => 'required',
            'fb_app_secret' => 'required'
        ]);

        //Validate facebook app details...
        $client = new Client();
        try{
            $response = $client->get('https://graph.facebook.com/oauth/access_token?client_id='.$request->get('fb_app_id').'&client_secret='.$request->get('fb_app_secret').'&grant_type=client_credentials');
            if($response->getStatusCode() == '200'){
                DotenvEditor::setKey('FACEBOOK_APP_ID', $request->get('fb_app_id'));
                DotenvEditor::setKey('FACEBOOK_APP_SECRET', $request->get('fb_app_secret'));

                //Save buffer to file...
                DotenvEditor::save();
                return redirect()->back()->with('success', __('Facebook credentials saved successfully!'));
            }else{
                return redirect()->back()->with('error', __('Invalid Facebook credentials. Verify and try again.'));
            }
        }catch(ClientException $e){
            return redirect()->back()->with('error', __('Invalid Facebook credentials. Verify and try again.'));
        }
    }

    public function login(LaravelFacebookSdk $fb)
    {
        try{
            // Send an array of permissions to request
            $login_url = $fb->getLoginUrl(['email', 'public_profile', 'user_about_me', 'user_friends', 'user_photos',
                'user_posts', 'user_videos', 'user_managed_groups', 'publish_actions', 'manage_pages', 'publish_pages',
                'pages_show_list']);

            return redirect()->to($login_url);
        }catch(FacebookSDKException $e){
            return redirect()->route('fb-accounts')->with('error', __('An error occurred! We could not connect to facebook. Please verify your API details and try again.'));
        }catch (\Exception $e){
            return redirect()->route('fb-accounts')->with('error', __('An error occurred! We could not connect to facebook. Please verify your API details and try again.'));
        }
    }

    public function refresh($facebook_id, LaravelFacebookSdk $fb)
    {
        try{
            // Send an array of permissions to request
            $login_url = $fb->getLoginUrl(['email', 'public_profile', 'user_about_me', 'user_friends', 'user_photos',
                'user_posts', 'user_videos', 'user_managed_groups', 'publish_actions', 'manage_pages', 'publish_pages',
                'pages_show_list']);
            Session::put('facebook_id', $facebook_id);
            return redirect()->to($login_url);
        }catch(FacebookSDKException $e){
            return redirect()->route('fb-accounts')->with('error', __('An error occurred! We could not connect to facebook. Please verify your API details and try again.'));
        }catch (\Exception $e){
            return redirect()->route('fb-accounts')->with('error', __('An error occurred! We could not connect to facebook. Please verify your API details and try again.'));
        }
    }

    public function callback(Request $request, LaravelFacebookSdk $fb)
    {
        // Obtain an access token.
        try {
            $token = $fb->getAccessTokenFromRedirect();

            // Access token will be null if the user denied the request
            // or if someone just hit this URL outside of the OAuth flow.
            if (!$token) {
                // Get the redirect helper
                $helper = $fb->getRedirectLoginHelper();

                if (!$helper->getError()) {
                    abort(403);
                }
            }

            if (!$token->isLongLived()) {
                // OAuth 2.0 client handler
                $oauth_client = $fb->getOAuth2Client();

                // Extend the access token.
                $token = $oauth_client->getLongLivedAccessToken($token);

            }

            // Get basic info on the user from Facebook.
            $response = $fb->get('/me?fields=id,name', $token);

            // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
            $facebook_user = $response->getGraphUser();
            $credentials = json_encode(array('access_token' => (string) $token));
            // Create a new facebook account
            if(!Session::has('facebook_id')){
                //Make sure similar account does not exist..
                $fb_check = FacebookAccount::where('profile_id', $facebook_user['id'])->first();
                if($fb_check){
                    return redirect()->route('fb-accounts')->with('error', __('Your facebook is already added!'));
                }

                $fb_account = new FacebookAccount();
            }else{
                $fb_account = FacebookAccount::find(Session::get('facebook_id'));
                if(!$fb_account){
                    $fb_account = new FacebookAccount();
                }else{
                    //Verify the facebook account doesnt exist...
                    if($fb_account->profile_id != $facebook_user['id']){
                        $fb_check = FacebookAccount::where('profile_id', $facebook_user['id'])->first();
                        if($fb_check){
                            return redirect()->route('fb-accounts')->with('error', __('Your facebook is already added!'));
                        }
                    }
                }
            }
            $fb_account->credentials = $credentials;
            $fb_account->profile_name = $facebook_user['name'];
            $fb_account->profile_id = (string) $facebook_user['id'];
            $fb_account->status = 'active';

            $url = 'http://graph.facebook.com/'.$facebook_user['id'].'/picture?type=large';
            if(!empty($url)){
                if($contents = file_get_contents($url)){
                    //Get image extension
                    $size = getimagesize($url);
                    $extension = image_type_to_extension($size[2]);
                    if($extension){
                        $name = 'upload/facebook/'.$facebook_user['id'].$extension;
                        Storage::disk('public_path')->put($name, $contents);

                        $fb_account->profile_picture = $name;
                    }
                }
            }

            $fb_account->save();

            //If this is a new login, we have to insert his/her group and pages...
            if(!Session::has('facebook_id')) {
                //Populate facebook groups...
                $response = $fb->get('/' . $facebook_user['id'] . '/groups', $token);

                $graphNode = $response->getGraphEdge();
                $groups = array();
                $groupArray = $graphNode->asArray();
                $groups = array_merge($groups, $groupArray);
                if ($fb->next($graphNode)) {
                    while ($graphNode = $fb->next($graphNode)) {
                        $groupArray = $graphNode->asArray();
                        $groups = array_merge($groups, $groupArray);
                    }
                }

                //Insert the groups...
                foreach($groups as $group){
                    //Make sure group does not exists...
                    $fb_group_check = FbGroup::where('group_id', $group['id'])->first();
                    if($fb_group_check){
                        continue;
                    }
                    $fb_group = new FbGroup();
                    $fb_group->facebook_id = $fb_account->id;
                    $fb_group->group_id = (string) $group['id'];
                    $fb_group->group_name = $group['name'];
                    $fb_group->status = 'active';

                    $fb_group->save();
                }


                //Populate facebook pages...
                $response = $fb->get('/' . $facebook_user['id'] . '/accounts', $token);
                $graphNode = $response->getGraphEdge();
                $pages = array();
                $pageArray = $graphNode->asArray();
                $pages = array_merge($pages, $pageArray);
                if ($fb->next($graphNode)) {
                    while ($graphNode = $fb->next($graphNode)) {
                        $pageArray = $graphNode->asArray();
                        $pages = array_merge($pages, $pageArray);
                    }
                }

                // Insert new fb pages
                foreach ($pages as $page){
                    // Make sure the page does not exists...
                    $fb_page_check = FbPage::where('page_id', $page['id'])->first();
                    if($fb_page_check){
                        continue;
                    }
                    $fb_page = new FbPage();
                    $fb_page->facebook_id = $fb_account->id;
                    $fb_page->page_id = (string) $page['id'];
                    $fb_page->page_name = $page['name'];
                    $fb_page->page_credentials = json_encode(array('access_token' => (string) $page['access_token']));
                    $fb_page->status = 'active';

                    $fb_page->save();
                }
            }


            Session::forget('facebook_id');
            return redirect()->route('fb-accounts')->with('success', 'Successfully logged in with Facebook');
        } catch (FacebookSDKException $e) {
            app('log')->debug($e->getMessage());
            return redirect()->route('fb-accounts')->with('error', __('Something went wrong while attempting to connect with your facebook account. Please try again later.'));
        }
    }
    public function delete($id)
    {
        $fb_account = FacebookAccount::find($id);
        if($fb_account){
            $name = $fb_account->profile_name;
            try{
                $fb_account->delete();
                return redirect()->back()->with('status', __('Facebook Account').' "'.$name.'" '.__('deleted successfully!'));
            }catch(\Exception $e){
                return redirect()->route('fb-accounts')->with('error', __('An error occurred while trying to delete! Try again.'));
            }
        }
    }
}
