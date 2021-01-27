<?php

namespace App\Console\Commands;

use App\MyLog;
use App\TwitterAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Facades\Twitter;

class FetchTwitterUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtain an array of followers/following details of all Twitter accounts';

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
        $accounts = TwitterAccount::where('status', 'active')
            ->get();
        foreach($accounts as $account) {
            $credentials = json_decode($account->credentials, true);

            // Set this user account as current
            $request_token = [
                'token' => $credentials['oauth_token'],
                'secret' => $credentials['oauth_token_secret'],
            ];

            Twitter::reconfig($request_token);

            try {

                if (!(Cache::has('tw_followers_' . $account->id) && count(Cache::get('tw_followers_' . $account->id)))) {
                    // Get all followers...
                    $followers = array();
                    $cursor = -1;
                    do {
                        $response = Twitter::get('followers/ids', array('cursor' => $cursor));
                        foreach ($response->ids as $id) {
                            $user = Twitter::getUsers(['user_id' => $id]);
                            $followers[(string)$id] = [$user->screen_name, $user->verified];
                        }
                        $cursor = $response->next_cursor;
                    } while ($cursor != 0);

                    // Get all following...
                    $following = array();
                    $cursor = -1;
                    do {
                        $response = Twitter::get('friends/ids', array('cursor' => $cursor));
                        foreach ($response->ids as $id) {
                            $user = Twitter::getUsers(['user_id' => $id]);
                            $following[(string)$id] = [$user->screen_name, $user->verified];
                        }
                        $cursor = $response->next_cursor;
                    } while ($cursor != 0);

                    // Strip out non_following...
                    $non_following = array_diff_key($followers, $following);
                    $non_followers = array_diff_key($following, $followers);

                    Cache::put('tw_following_' . $account->id, $following, now()->addMinutes(10));
                    Cache::put('tw_followers_' . $account->id, $followers, now()->addMinutes(10));
                    Cache::put('tw_non_following_' . $account->id, $non_following, now()->addMinutes(10));
                    Cache::put('tw_non_followers_' . $account->id, $non_followers, now()->addMinutes(10));

                    // Update Followers and Following details
                    $account->following = count($following);
                    $account->followers = count($followers);
                    $account->save();

                    $this->info('Fetched Twitter users of '.$account->profile_name);
                }

            } catch (\Exception $e) {
                Log::debug($e->getMessage());

                //Write into my local logs
                $this->logTw('danger', __('An error occurred while fetching your twitter details! Message:') . ' ' . $e->getMessage());
                $this->error(__('An error occurred while fetching your twitter details! Message:') . ' ' . $e->getMessage());
                continue;
            }
        }
        $this->line('Completed Twitter users fetch!');
    }

    public function logTw($state, $message)
    {
        $my_log = new MyLog();
        $my_log->type = 'tw';
        $my_log->state = $state;
        $my_log->message = $message;
        $my_log->save();
    }
}
