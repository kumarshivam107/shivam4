<?php

namespace App\Http\Controllers\Settings;

use App\TwitterAccount;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use RunTimeException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\Exceptions\KeyNotFoundException;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Thujohn\Twitter\Facades\Twitter;

class TwitterAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //Check if facebook api env key exists...
        if (!DotenvEditor::keyExists('TWITTER_ACCESS_TOKEN')) {
            DotenvEditor::addEmpty();
            DotenvEditor::setKey('TWITTER_ACCESS_TOKEN');
        }
        if (!DotenvEditor::keyExists('TWITTER_ACCESS_TOKEN_SECRET')) {
            DotenvEditor::setKey('TWITTER_ACCESS_TOKEN_SECRET');
        }
        if (!DotenvEditor::keyExists('TWITTER_CONSUMER_KEY')) {
            DotenvEditor::setKey('TWITTER_CONSUMER_KEY');
        }
        if (!DotenvEditor::keyExists('TWITTER_CONSUMER_SECRET')) {
            DotenvEditor::setKey('TWITTER_CONSUMER_SECRET');
        }

        //Save buffer to file...
        DotenvEditor::save();

        //Check current status of the facebook api, if exists...
        try {
            if (!empty(DotenvEditor::getValue('TWITTER_ACCESS_TOKEN')) &&
                !empty(DotenvEditor::getValue('TWITTER_ACCESS_TOKEN_SECRET')) &&
                !empty(DotenvEditor::getValue('TWITTER_CONSUMER_KEY')) &&
                !empty(DotenvEditor::getValue('TWITTER_CONSUMER_SECRET'))
            ){
                $credentials = Twitter::getCredentials();
                $api_status = 'Active';
            }
        }catch(KeyNotFoundException $e){
            DotenvEditor::setKey('TWITTER_ACCESS_TOKEN');
            DotenvEditor::setKey('TWITTER_ACCESS_TOKEN_SECRET');
            DotenvEditor::setKey('TWITTER_CONSUMER_KEY');
            DotenvEditor::setKey('TWITTER_CONSUMER_SECRET');
        }catch(RunTimeException $e){
            $api_status = 'Inactive';
        }

        return view('settings.twitter-api', compact('api_status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tw_access_token' => 'required',
            'tw_access_token_secret' => 'required',
            'tw_consumer_key' => 'required',
            'tw_consumer_secret' => 'required'
        ]);

        // Verify twitter credentials ...
        Twitter::reconfig(
            [
                'token' => $request->get('tw_access_token'),
                'secret' => $request->get('tw_access_token_secret'),
                'consumer_key' => $request->get('tw_consumer_key'),
                'consumer_secret' => $request->get('tw_consumer_secret')
            ]);

        try{
            $credentials = Twitter::getCredentials();
            if(isset($credentials->id) && !empty($credentials->id)){
                DotenvEditor::setKey('TWITTER_ACCESS_TOKEN', $request->get('tw_access_token'));
                DotenvEditor::setKey('TWITTER_ACCESS_TOKEN_SECRET', $request->get('tw_access_token_secret'));
                DotenvEditor::setKey('TWITTER_CONSUMER_KEY', $request->get('tw_consumer_key'));
                DotenvEditor::setKey('TWITTER_CONSUMER_SECRET', $request->get('tw_consumer_secret'));

                //Save buffer to file...
                DotenvEditor::save();
                return redirect()->back()->with('success', __('Twitter credentials saved successfully!'));
            }
        }catch(RunTimeException $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function login()
    {
        $sign_in_twitter = true;
        $force_login = false;
        try {
            // Make sure we make this request w/o tokens, overwrite the default values in case of login.
            Twitter::reconfig(['token' => '', 'secret' => '']);
            $token = Twitter::getRequestToken(route('twitter.callback'));

            if (isset($token['oauth_token_secret'])) {
                $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

                Session::put('oauth_state', 'start');
                Session::put('oauth_request_token', $token['oauth_token']);
                Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

                return redirect()->to($url);
            }
        }catch(\Exception $e){
            return redirect()->route('tw-accounts')->with('error', __('We could not log on to Twitter. Please verify your API details.'));
        }
    }

    public function refresh($twitter_id)
    {
        $sign_in_twitter = true;
        $force_login = false;
        Session::put('twitter_id', $twitter_id);
        try{
            // Make sure we make this request w/o tokens, overwrite the default values in case of login.
            Twitter::reconfig(['token' => '', 'secret' => '']);
            $token = Twitter::getRequestToken(route('twitter.callback'));

            if (isset($token['oauth_token_secret']))
            {
                $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

                Session::put('oauth_state', 'start');
                Session::put('oauth_request_token', $token['oauth_token']);
                Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

                return redirect()->to($url);
            }
        }catch(Exception $e){
            return redirect()->route('tw-accounts')->with('error', __('We could not log on to Twitter. Please verify your API details.'));
        }
    }

    public function callback()
    {
        if (Session::has('oauth_request_token'))
        {
            try{
                $request_token = [
                    'token'  => Session::get('oauth_request_token'),
                    'secret' => Session::get('oauth_request_token_secret'),
                ];

                Twitter::reconfig($request_token);

                $oauth_verifier = false;

                if (Input::has('oauth_verifier'))
                {
                    $oauth_verifier = Input::get('oauth_verifier');
                    try{
                        // getAccessToken() will reset the token for you
                        $token = Twitter::getAccessToken($oauth_verifier);
                    }catch (RunTimeException $e){
                        return redirect()->route('tw-accounts')->with('error', __('We were unable to get your token from twitter! Please try again later.'));
                    }
                }

                if (!isset($token['oauth_token_secret']))
                {
                    return redirect()->route('tw-accounts')->with('error', 'We could not log you in on Twitter.');
                }

                $credentials = Twitter::getCredentials();

                if (is_object($credentials) && !isset($credentials->error))
                {
                    //Create a new twitter account...
                    if(!Session::has('twitter_id')){
                        //Make sure similar account does not exist..
                        $tw_check = TwitterAccount::where('profile_id', $credentials->id)->first();
                        if($tw_check){
                            return redirect()->route('tw-accounts')->with('error', __('Your twitter is already added!'));
                        }

                        $tw_account = new TwitterAccount();
                    }else{
                        $tw_account = TwitterAccount::find(Session::get('twitter_id'));
                        if(!$tw_account){
                            $tw_account = new TwitterAccount();
                        }else{
                            //Verify the twitter account doesnt exist...
                            if($tw_account->profile_id != $credentials->id){
                                $tw_check = TwitterAccount::where('profile_id', $credentials->id)->first();
                                if($tw_check){
                                    return redirect()->route('tw-accounts')->with('error', __('Your twitter is already added!'));
                                }
                            }
                        }
                    }
                    $id = $credentials->id;
                    $tw_account->credentials = json_encode($token);
                    $tw_account->profile_id = (string) $credentials->id;
                    $tw_account->profile_name = $credentials->name;

                    //Fetch twitter profile picture..
                    $url = $credentials->profile_image_url;

                    if(!empty($url)){
                        $url = str_replace('_normal', '', $url);
                        if($contents = file_get_contents($url)){
                            $name = 'upload/twitter/'.basename($url);
                            Storage::disk('public_path')->put($name, $contents);

                            $tw_account->profile_picture = $name;
                        }
                    }
                    $tw_account->status = 'active';
                    $tw_account->following = $credentials->friends_count;
                    $tw_account->followers = $credentials->followers_count;

                    $tw_account->save();

                    Session::forget('twitter_id');
                    return redirect()->route('tw-accounts')->with('success', __('Congrats! You\'ve successfully added your account!'));
                }else{
                    return redirect()->route('tw-accounts')->with('error', __('Something went wrong while adding your twitter account!'));
                }
            }catch(RunTimeException $e){
                return redirect()->route('tw-accounts')->with('error', __('We were unable to get your credentials from twitter!'));
            }catch(Exception $e){
                return redirect()->route('tw-accounts')->with('error', __('We could not log on to Twitter. Please verify your API details.'));
            }
        }else{
            return redirect()->route('tw-accounts')->with('error', __('We could not log on to Twitter. Please verify your API details.'));
        }
    }

    public function delete($id)
    {
        $tw_account = TwitterAccount::find($id);
        if($tw_account){
            $name = $tw_account->profile_name;
            try{
                $tw_account->delete();
                return redirect()->back()->with('status', __('Twitter Account').' "'.$name.'" '.__('deleted successfully!'));
            }catch(Exception $e){
                return redirect()->route('tw-accounts')->with('error', __('An error occurred while trying to delete! Try again.'));
            }
        }
    }
}
