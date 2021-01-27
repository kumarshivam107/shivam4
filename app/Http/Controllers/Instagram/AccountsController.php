<?php

namespace App\Http\Controllers\Instagram;

use App\DmLog;
use App\FollowBackLog;
use App\InstagramAccount;
use App\PostLog;
use App\UnfollowLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Instagram;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ig_accounts = InstagramAccount::paginate(3);

        $post_count = PostLog::where('type', 'ig')->count();
        $dm_count = DmLog::where('type', 'ig')->count();
        $follow_back_count = FollowBackLog::where('type', 'ig')->count();
        $unfollow_count = UnfollowLog::where('type', 'ig')->count();

        $follow_back_stat = FollowBackLog::where('type', 'ig')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        $unfollow_stat = UnfollowLog::where('type', 'ig')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        return view('instagram.accounts',
            compact(
                'ig_accounts',
                'post_count',
                'dm_count',
                'follow_back_count',
                'unfollow_count',
                'follow_back_stat',
                'unfollow_stat'
            ));
    }

    public function store(Request $request, Instagram $ig)
    {
        $this->validate($request, [
           'ig_username' => 'required',
           'ig_password' => 'required'
        ]);

        $username = $request->get('ig_username');
        $password = $request->get('ig_password');

        // $proxy = 'https://'.$request->getClientIp().':443';
        try {
            // $ig->setProxy($proxy);

            $loginResponse = $ig->login($username, $password);

            if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
                // Two Factor login is not recommended
                return redirect()->back()->with('error', __('Kindly disable two-factor login from your Instagram Account, before you can continue!'));
            }
        }catch(IncorrectPasswordException $e){
            return redirect()->back()->with('error', __('Your Instagram password was incorrect!'));
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while attempting to Login. Message:').' '.$e->getMessage());
        }

        try {
            $response = $ig->account->getCurrentUser();
            $user = $response->getUser();

            // Create a new instagram account...
            // Make sure similar account does not exist..
            $ig_check = InstagramAccount::where('profile_id', $user->getPk())->first();
            if($ig_check){
                return redirect()->route('ig-accounts')->with('error', __('Your instagram is already added!'));
            }

            $ig_account = new InstagramAccount();

            // Insert new Instagram Account...
            $ig_account->credentials = json_encode(['username' => $username, 'password' => Crypt::encryptString($password)]);
            $ig_account->profile_id = (string) $user->getPk();
            $ig_account->profile_name = $user->getFullName();
            $ig_account->followers = $user->getFollowerCount();
            $ig_account->following = $user->getFollowingCount();
            $ig_account->status = 'active';

            // Get Profile Pic...
            $url = $user->getProfilePicUrl();
            if (!empty($url)) {
                if ($contents = file_get_contents($url)) {
                    $name = 'upload/instagram/' . basename($url);
                    Storage::disk('public_path')->put($name, $contents);

                    $ig_account->profile_picture = $name;
                }
            }

            $ig_account->save();

            // Store the proxy for this particular instagram connection...
            /*
            if($proxy){
                Cache::forever('ig_proxy_'.$ig_account->id, $proxy);
            }
            */
            return redirect()->route('ig-accounts')
                ->with('success', __('Congrats! You\'ve successfully added your account!'))
                ->with('status', __('Note! Some of your info may not be available, simply because the server did not send it. We\'ll try to obtain them later'));
        }catch (\Exception $e){
            return redirect()->back()->with('error', __('An error occurred while trying to obtain your profile info!'));
        }
    }

    public function refresh(Request $request, Instagram $ig)
    {
        $this->validate($request, [
            'instagram_id' => 'required|numeric',
            'ig_username' => 'required',
            'ig_password' => 'required'
        ]);

        $username = $request->get('ig_username');
        $password = $request->get('ig_password');
        try {
            $loginResponse = $ig->login($username, $password, true);

            if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
                // Two Factor login is not recommended
                return redirect()->back()->with('error', __('Kindly disable two-factor login from your Instagram Account, before you can continue!'));
            }
        }catch(IncorrectPasswordException $e){
            return redirect()->back()->with('error', 'Your Instagram password was incorrect!');
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while attempting to Login. Please try again later!');
        }

        try {
            $response = $ig->account->getCurrentUser();
            $user = $response->getUser();


            $ig_account = InstagramAccount::find($request->get('instagram_id'));
            if(!$ig_account){
                $ig_account = new InstagramAccount();
            }else{
                //Verify the instagram account doesn't exist...
                if($ig_account->profile_id != $user->getPk()){
                    $ig_check = InstagramAccount::where('profile_id', $user->getPk())->first();
                    if($ig_check){
                        return redirect()->route('ig-accounts')->with('error', __('Your instagram is already added!'));
                    }
                }
            }

            //Insert new Instagram Account...
            $ig_account->credentials = json_encode(['username' => $username, 'password' => Crypt::encryptString($password)]);
            $ig_account->profile_id = $user->getPk();
            $ig_account->profile_name = $user->getFullName();
            $ig_account->followers = $user->getFollowerCount();
            $ig_account->following = $user->getFollowingCount();
            $ig_account->status = 'active';

            // Get Profile Pic...
            $url = $user->getProfilePicUrl();
            if (!empty($url)) {
                if ($contents = file_get_contents($url)) {
                    $name = 'upload/instagram/' . basename($url);
                    Storage::disk('public_path')->put($name, $contents);

                    $ig_account->profile_picture = $name;
                }
            }

            $ig_account->save();
            return redirect()->route('ig-accounts')
                ->with('success', __('Congrats! You\'ve successfully refreshed your account!'))
                ->with('status', __('Note! Some of your info may not be available, simply because the server did not send it. We\'ll try to obtain them later'));
        }catch (\Exception $e){
            return redirect()->back()->with('error', __('An error occurred while trying to obtain your profile info!'));
        }
    }

    public function delete($id)
    {
        $ig_account = InstagramAccount::find($id);
        if($ig_account){
            $name = $ig_account->profile_name;
            try{
                $ig_account->delete();
                return redirect()->back()->with('status', __('Instagram Account').' "'.$name.'" '.__('deleted successfully!'));
            }catch(\Exception $e){
                return redirect()->route('ig-accounts')->with('error', __('An error occurred while trying to delete! Try again.'));
            }
        }
    }
}
