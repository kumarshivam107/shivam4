<?php

namespace App\Console\Commands;

use App\IgUnfollow;
use App\MyLog;
use App\UnfollowLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\NotFoundException;
use InstagramAPI\Instagram;

class IgUnfollowSchedule extends Command
{
    protected $ig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:ig_unfollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes all scheduled Instagram Unfollow in the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Instagram $ig)
    {
        parent::__construct();
        $this->ig = $ig;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ig = $this->ig;

        $unfollow_queue = IgUnfollow::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();
        foreach ($unfollow_queue as $unfollow){
            $account = $unfollow->instagram_account()->where('status', 'active')->first();
            if($account) {
                $credentials = json_decode($account->credentials, true);

                // Get instagram statistics
                try {
                    //Obtain Login details...
                    $username = $credentials['username'];
                    $password = Crypt::decryptString($credentials['password']);

                    /*
                    // Try to get the proxy of this instagram account...
                    if(Cache::has('ig_proxy_'.$account->id) && !empty(Cache::get('ig_proxy_'.$account->id))){
                        $proxy = Cache::get('ig_proxy_'.$account->id);
                        $ig->setProxy($proxy);
                    }
                    */

                    $ig->login($username, $password);

                    //!(Cache::has('ig_non_following_'.$account->id) && count(Cache::get('ig_non_following_'.$account->id)))
                    if( !(Cache::has('ig_non_followers_'.$account->id) && count(Cache::get('ig_non_followers_'.$account->id))) ) {

                        // Get all following...
                        $maxID = null;
                        $following = array();
                        do {
                            $info = $ig->people->getSelfFollowing(null, $maxID);
                            foreach ($info->getUsers() as $user) {
                                if (!empty($user->getUsername()) && !($user->isIsPrivate() || (boolean) $user->getIsPrivate())) {
                                    $pk = $user->getPk();
                                    $key = (isset($pk) && !empty($pk) && strlen($pk) > 2) ? $pk : $ig->people->getUserIdForName($user->getUsername());
                                    $following[$key] = [$user->getUsername(), (boolean) ($user->getIsVerified() || $user->isIsVerified())];
                                }
                            }
                        } while (!is_null($maxID = $info->getNextMaxId()));

                        // Get all followers...
                        $maxID = null;
                        $followers = array();
                        do {
                            $info = $ig->people->getSelfFollowers(null, $maxID);
                            foreach ($info->getUsers() as $user) {
                                if (!empty($user->getUsername()) && !($user->isIsPrivate() || (boolean) $user->getIsPrivate())) {
                                    $pk = $user->getPk();
                                    $key = (isset($pk) && !empty($pk) && strlen($pk) > 2) ? $pk : $ig->people->getUserIdForName($user->getUsername());
                                    $followers[$key] = [$user->getUsername(), (boolean) ($user->getIsVerified() || $user->isIsVerified())];
                                }
                            }
                        } while (!is_null($maxID = $info->getNextMaxId()));

                        // Strip out non_following...
                        $non_following = array_diff_key($followers, $following);
                        $non_followers = array_diff_key($following, $followers);

                        Cache::put('ig_following_'.$account->id, $following, now()->addMinutes(10));
                        Cache::put('ig_followers_'.$account->id, $followers, now()->addMinutes(10));
                        Cache::put('ig_non_following_'.$account->id, $non_following, now()->addMinutes(10));
                        Cache::put('ig_non_followers_'.$account->id, $non_followers, now()->addMinutes(10));
                    }

                    $people = Cache::get('ig_non_followers_'.$account->id);


                    $i = 1;
                    foreach ($people as $key => $value) {
                        if($i >= 6){
                            break;
                        }

                        // Perform Exception checks...
                        $exception = explode(',', $unfollow->exception);
                        if(in_array($key, $exception)){
                            continue;
                        }

                        // Exclude Verified users, if set...
                        if($unfollow->exclude_verified == 'yes' && $value[1]){
                            continue;
                        }

                        // Exclude Non-verified users, if set...
                        if($unfollow->exclude_non_verified == 'yes' && !$value[1]){
                            continue;
                        }

                        $ig->people->unfollow($key);

                        //Write into Logs
                        $this->unfollowLog('ig');
                        $this->logIg('success', __('Unfollowed').' "'.$value[0].'" '.__('on Instagram on behalf of').' "'.$account->profile_name.'"');
                        $this->info(__('Unfollowed').' "'.$value[0].'" '.__('on Instagram on behalf of').' "'.$account->profile_name.'"');

                        // Remove if already followed...
                        unset($people[$key]);
                        $i++;
                    }

                    Cache::put('ig_non_followers_'.$account->id, $people, now()->addMinutes(10));
                } catch(IncorrectPasswordException $e){
                    Log::debug($e->getMessage());

                    //Write into my local logs
                    $this->logIg('danger', __('The password of the Instagram Account').' "'.$account->profile_name.'" '.__('is incorrect! Refresh the account and try again.'));
                    $this->error(__('The password of the Instagram Account').' "'.$account->profile_name.'" '.__('is incorrect! Refresh the account and try again.'));
                    $account->status = 'inactive';
                    $account->save();
                    continue;
                }  catch(NetworkException $e){
                    Log::debug($e->getMessage());
                    $this->logIg('danger', __('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    $this->error(__('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    continue;
                } catch(NotFoundException $e){
                    Log::debug($e->getMessage());
                    $this->logIg('danger', __('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    $this->error(__('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    continue;
                } catch (\Exception $e) {
                    Log::debug($e->getMessage());

                    $this->logIg('danger', __('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    $this->error(__('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
                    $unfollow->status = 'inactive';
                    $unfollow->save();
                    continue;
                }
            }
        }
        $this->line('Completed execution of unfollow schedule!');
    }

    /*
    * Log Helper Functions
    *
    * The following are used to store logs into
    * the database
    */
    public function logIg($state, $message)
    {
        $my_log = new MyLog();
        $my_log->type = 'ig';
        $my_log->state = $state;
        $my_log->message = $message;
        $my_log->save();
    }

    public function unfollowLog($type)
    {
        $post_log = new UnfollowLog();
        $post_log->type = $type;
        $post_log->save();
    }
}
