<?php

namespace App\Console\Commands;

use App\MyLog;
use App\TwUnfollow;
use App\UnfollowLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Facades\Twitter;

class TwUnfollowSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:tw_unfollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes all scheduled Twitter Unfollow in the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unfollow_queue = TwUnfollow::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        foreach ($unfollow_queue as $unfollow) {
            $account = $unfollow->twitter_account()->where('status', 'active')->first();
            if ($account) {
                $credentials = json_decode($account->credentials, true);

                // Set this user account as current
                $request_token = [
                    'token' => $credentials['oauth_token'],
                    'secret' => $credentials['oauth_token_secret'],
                ];

                Twitter::reconfig($request_token);

                try {

                    if( !(Cache::has('tw_non_followers_'.$account->id) && count(Cache::get('tw_non_followers_'.$account->id))) ) {
                        // Get all followers...
                        $followers = array();
                        $cursor = -1;
                        do{
                            $response = Twitter::get('followers/ids', array('cursor' => $cursor));
                            foreach ($response->ids as $id) {
                                $user = Twitter::getUsers(['user_id' => $id]);
                                $followers[(string) $id] = [$user->screen_name, $user->verified];
                            }
                            $cursor = $response->next_cursor;
                        }while($cursor != 0);

                        // Get all following...
                        $following = array();
                        $cursor = -1;
                        do{
                            $response = Twitter::get('friends/ids', array('cursor' => $cursor));
                            foreach ($response->ids as $id) {
                                $user = Twitter::getUsers(['user_id' => $id]);
                                $following[(string) $id] = [$user->screen_name, $user->verified];
                            }
                            $cursor = $response->next_cursor;
                        }while($cursor != 0);

                        // Strip out non_following...
                        $non_following = array_diff_key($followers, $following);
                        $non_followers = array_diff_key($following, $followers);

                        Cache::put('tw_following_'.$account->id, $following, now()->addMinutes(10));
                        Cache::put('tw_followers_'.$account->id, $followers, now()->addMinutes(10));
                        Cache::put('tw_non_following_'.$account->id, $non_following, now()->addMinutes(10));
                        Cache::put('tw_non_followers_'.$account->id, $non_followers, now()->addMinutes(10));
                    }

                    $people = Cache::get('tw_non_followers_'.$account->id);

                    $i = 1;
                    foreach ($people as $key => $value) {
                        if($i >= 25){
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

                        $response = Twitter::postUnfollow(['user_id' => $key]);

                        // Write into Logs
                        $this->unfollowLog('tw');
                        $this->logTw('success', __('Unfollowed') . ' "' . $value[0] . '" ' . __('on Twitter on behalf of') . ' "' . $account->profile_name . '"');
                        $this->info(__('Unfollowed') . ' "' . $value[0] . '" ' . __('on Twitter on behalf of') . ' "' . $account->profile_name . '"');

                        // Remove if already followed...
                        unset($people[$key]);
                        $i++;
                    }
                    Cache::put('tw_non_followers_'.$account->id, $people, now()->addMinutes(10));
                }catch (\Exception $e){
                    Log::debug($e->getMessage());

                    //Write into my local logs
                    $this->logTw('danger', __('An error occurred while executing schedule Unfollow! Message:').' '.$e->getMessage());
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

    public function logTw($state, $message)
    {
        $my_log = new MyLog();
        $my_log->type = 'tw';
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
