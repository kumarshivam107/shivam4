<?php

namespace App\Console\Commands;

use App\InstagramAccount;
use App\MyLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\NotFoundException;
use InstagramAPI\Instagram;

class FetchInstagramUsers extends Command
{
    protected $ig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:instagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtain an array of followers/following details of all Instagram accounts';

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
        $accounts = InstagramAccount::where('status', 'active')
            ->get();
        foreach($accounts as $account) {
            $credentials = json_decode($account->credentials, true);

            // Get instagram statistics...
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

                if (!(Cache::has('ig_followers_' . $account->id) && count(Cache::get('ig_followers_' . $account->id)))) {

                    // Get all following...
                    $maxID = null;
                    $following = array();
                    do {
                        $info = $ig->people->getSelfFollowing(null, $maxID);
                        foreach ($info->getUsers() as $user) {
                            if (!empty($user->getUsername()) && !($user->isIsPrivate() || (boolean)$user->getIsPrivate())) {
                                $pk = $user->getPk();
                                $key = (isset($pk) && !empty($pk) && strlen($pk) > 2) ? $pk : $ig->people->getUserIdForName($user->getUsername());
                                $following[$key] = [$user->getUsername(), (boolean)($user->getIsVerified() || $user->isIsVerified())];
                            }
                        }
                    } while (!is_null($maxID = $info->getNextMaxId()));

                    // Get all followers...
                    $maxID = null;
                    $followers = array();
                    do {
                        $info = $ig->people->getSelfFollowers(null, $maxID);
                        foreach ($info->getUsers() as $user) {
                            if (!empty($user->getUsername()) && !($user->isIsPrivate() || (boolean)$user->getIsPrivate())) {
                                $pk = $user->getPk();
                                $key = (isset($pk) && !empty($pk) && strlen($pk) > 2) ? $pk : $ig->people->getUserIdForName($user->getUsername());
                                $followers[$key] = [$user->getUsername(), (boolean)($user->getIsVerified() || $user->isIsVerified())];
                            }
                        }
                    } while (!is_null($maxID = $info->getNextMaxId()));

                    // Strip out non_following...
                    $non_following = array_diff_key($followers, $following);
                    $non_followers = array_diff_key($following, $followers);

                    Cache::put('ig_following_' . $account->id, $following, now()->addMinutes(10));
                    Cache::put('ig_followers_' . $account->id, $followers, now()->addMinutes(10));
                    Cache::put('ig_non_following_' . $account->id, $non_following, now()->addMinutes(10));
                    Cache::put('ig_non_followers_' . $account->id, $non_followers, now()->addMinutes(10));

                    // Update Followers and Following details
                    $account->following = count($following);
                    $account->followers = count($followers);
                    $account->save();

                    $this->info('Fetched Instagram users of '.$account->profile_name);
                }

            } catch (IncorrectPasswordException $e) {
                Log::debug($e->getMessage());

                //Write into my local logs
                $this->logIg('danger', __('The password of the Instagram Account') . ' "' . $account->profile_name . '" ' . __('is incorrect! Refresh the account and try again.'));
                $this->error( __('The password of the Instagram Account') . ' "' . $account->profile_name . '" ' . __('is incorrect! Refresh the account and try again.'));
                $account->status = 'inactive';
                $account->save();
                continue;
            } catch (NetworkException $e) {
                Log::debug($e->getMessage());
                $this->logIg('danger', __('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                $this->error(__('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                continue;
            } catch (NotFoundException $e) {
                Log::debug($e->getMessage());
                $this->logIg('danger', __('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                $this->error(__('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                continue;
            } catch (\Exception $e) {
                Log::debug($e->getMessage());

                $this->logIg('danger', __('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                $this->error(__('An error occurred while fetching your Instagram details! Message:') . ' ' . $e->getMessage());
                continue;
            }

        }
        $this->line('Completed Instagram users fetch!');
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
}
