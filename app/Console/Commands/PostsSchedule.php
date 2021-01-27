<?php

namespace App\Console\Commands;

use App\FacebookAccount;
use App\FbGroup;
use App\FbPage;
use App\InstagramAccount;
use App\MyLog;
use App\PostLog;
use App\PostQueue;
use App\TwitterAccount;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Instagram;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Thujohn\Twitter\Facades\Twitter;

class PostsSchedule extends Command
{
    protected $fb;
    protected $ig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes all scheduled posts in the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Instagram $ig, LaravelFacebookSdk $fb)
    {
        parent::__construct();

        $this->ig = $ig;
        $this->fb = $fb;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ig = $this->ig;
        $fb = $this->fb;

        // Get all active posts queue...
        //
        $post_queue = PostQueue::where('status', 'active')
            ->where('schedule_time', '<=', Carbon::now())
            ->get();
        foreach($post_queue as $post) {
            // Explode all files into array :)...
            $image_files = explode(',', $post->image_file);
            foreach ($image_files as $key => $value){
                if(!$value || empty($value)){
                    unset($image_files[$key]);
                }
            }

            $video_files = explode(',', $post->video_file);
            foreach ($video_files as $key => $value){
                if(!$value || empty($value)){
                    unset($video_files[$key]);
                }
            }

            //Validate image and video file...
            if (!empty($post->image_file)) {
                foreach($image_files as $image_file){
                    if (File::exists(public_path($image_file))) {
                        // Make sure that this is actually an image...
                        $allowed_types = ['image/jpeg','image/jpg', 'image/png'];
                        $mime_type = mime_content_type(public_path($image_file));

                        if (!in_array($mime_type, $allowed_types)) {
                            $post->status = 'failed';
                            $post->save();

                            continue;
                        }

                        // Validate Image Aspect Ratio for instagram...
                        if (!empty($post->instagram_ids)) {
                            if (list($width, $height, $type, $attr) = getimagesize(public_path($image_file))) {
                                if (($width / max($height, 1)) != 1) {
                                    $post->status = 'failed';
                                    $post->save();

                                    continue;
                                }

                                if ($width > 1000 || $width < 350 || $height > 1000 || $height < 350) {
                                    $post->status = 'failed';
                                    $post->save();

                                    continue;
                                }
                            }
                        }
                    } else {
                        $post->status = 'failed';
                        $post->save();

                        continue;
                    }
                }
            }

            //Verify video file
            if (!empty($post->video_file)) {
                foreach($video_files as $video_file){
                    if (File::exists(public_path($video_file))) {
                        // Make sure that this is actually a video...
                        $allowed_types = ['video/mp4', 'video/3gpp2'];
                        $mime_type = mime_content_type(public_path($video_file));

                        if (!in_array($mime_type, $allowed_types)) {
                            $post->status = 'failed';
                            $post->save();

                            continue;
                        }
                    } else {
                        $post->status = 'failed';
                        $post->save();

                        continue;
                    }
                }
            }


            // Post to all Instagram Account...
            $instagram_ids = explode(',', $post->instagram_ids);
            if (isset($instagram_ids) && count($instagram_ids)) {
                foreach ($instagram_ids as $instagram_id) {
                    $ig_account = InstagramAccount::where('status', 'active')->find($instagram_id);
                    if ($ig_account) {
                        $credentials = json_decode($ig_account->credentials, true);
                        try {
                            //Obtain Login details...
                            $username = $credentials['username'];
                            $password = Crypt::decryptString($credentials['password']);

                            /*
                            // Try to get the proxy of this instagram account...
                            if(Cache::has('ig_proxy_'.$ig_account->id) && !empty(Cache::get('ig_proxy_'.$ig_account->id))){
                                $proxy = Cache::get('ig_proxy_'.$ig_account->id);
                                $ig->setProxy($proxy);
                            }
                            */

                            $ig->login($username, $password);

                            // Post as single...
                            if($post->ig_single == 'yes' && count($image_files)){
                                foreach($image_files as $image_file){
                                    $ig->timeline->uploadPhoto(public_path($image_file), ['caption' => $post->msg_body]);
                                }
                            }

                            // Post as story...
                            if($post->ig_story == 'yes' && count($image_files)){
                                $media = array();
                                foreach($image_files as $image_file){
                                    $media[] = [
                                        'type'     => 'photo',
                                        'file'     => public_path($image_file), // Path to the photo file.
                                    ];
                                }
                                $ig->timeline->uploadAlbum($media, ['caption' => $post->msg_body]);
                            }

                            // Log into post_logs
                            $this->postLog('ig');
                            $this->logIg('success', __('Posted on Instagram Account:') . ' ' . $ig_account->profile_name);
                            $this->info(__('Posted on Instagram Account:') . ' ' . $ig_account->profile_name);
                        } catch (IncorrectPasswordException $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logIg('danger', __('The password of the Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('is incorrect! Refresh the account and try again.'));
                            $this->error(__('The password of the Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('is incorrect! Refresh the account and try again.'));

                            $ig_account->status = 'inactive';
                            $ig_account->save();
                            continue;
                        } catch (AccountDisabledException $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logIg('danger', __('The Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('is disabled! Refresh the account and try again.'));
                            $this->error(__('The Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('is disabled! Refresh the account and try again.'));

                            $ig_account->status = 'inactive';
                            $ig_account->save();
                            continue;
                        } catch (\Exception $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logIg('danger', __('Unable to post on the Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            $this->error(__('Unable to post on the Instagram Account') . ' "' . $ig_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }

            $twitter_ids = explode(',', $post->twitter_ids);
            //Post to all twitter accounts...
            if (isset($twitter_ids) && count($twitter_ids)) {
                foreach ($twitter_ids as $twitter_id) {
                    $tw_account = TwitterAccount::where('status', 'active')->find($twitter_id);
                    if ($tw_account) {
                        $credentials = json_decode($tw_account->credentials, true);

                        // Set this user account as current
                        $request_token = [
                            'token' => $credentials['oauth_token'],
                            'secret' => $credentials['oauth_token_secret'],
                        ];

                        Twitter::reconfig($request_token);

                        try {
                            //If the post has a media, we have to post as a media
                            if ($post->tw_media == 'yes' && count($image_files)) {
                                foreach ($image_files as $image_file){
                                    $uploaded_media = Twitter::uploadMedia(['media' => File::get(public_path($image_file))]);
                                    Twitter::postTweet(['status' => $post->msg_body, 'media_ids' => $uploaded_media->media_id_string]);
                                }
                            }

                            // Post a normal tweet...
                            if ($post->tw_status == 'yes') {
                                Twitter::postTweet(['status' => $post->msg_body, 'format' => 'json']);
                            }

                            // Log the new post...
                            $this->postLog('tw');
                            $this->logTw('success', __('Posted on Twitter Account:') . ' ' . $tw_account->profile_name);
                            $this->info(__('Posted on Twitter Account:') . ' ' . $tw_account->profile_name);
                        } catch (\Exception $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logTw('danger', __('Unable to post on the Twitter Account') . ' "' . $tw_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            $this->error(__('Unable to post on the Twitter Account') . ' "' . $tw_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }

            $facebook_ids = explode(',', $post->facebook_ids);
            //Post to all facebook account...
            if (isset($facebook_ids) && count($facebook_ids)) {
                foreach ($facebook_ids as $facebook_id) {
                    $fb_account = FacebookAccount::where('status', 'active')->find($facebook_id);
                    if ($fb_account) {
                        $credentials = json_decode($fb_account->credentials, true);

                        $access_token = $credentials['access_token'];

                        try {
                            // Post as media
                            if($post->fb_media == 'yes') {
                                // If image is existing, post as an image...
                                if (count($image_files)) {
                                    foreach($image_files as $image_file) {
                                        $data = ['source' => $fb->fileToUpload(public_path($image_file)),
                                            'message' => $post->msg_body];
                                        $fb->post('/me/photos', $data, $access_token);
                                    }
                                }

                                // If video is existing, share..
                                if (count($video_files)) {
                                    foreach($video_files as $video_file){
                                        $data = ['title' => $post->video_title, 'description' => $post->msg_body,
                                            'source' => $fb->videoToUpload(public_path($video_file))];
                                        $fb->post('/me/videos', $data, $access_token);
                                    }
                                }
                            }

                            // Post as Status...
                            if ($post->fb_status == 'yes') {
                                $data = ['message' => $post->msg_body];
                                $fb->post('/me/feed', $data, $access_token);
                            }

                            // Post as Link...
                            if($post->fb_link == 'yes') {
                                $data = [
                                    'link' => $post->link_url,
                                    'message' => __('Description:') . ' ' . $post->msg_body,
                                ];
                                $fb->post('/me/feed', $data,  $access_token);
                            }

                            // Log the new post...
                            $this->postLog('fb');
                            $this->logFb('success', __('Posted on Facebook Account:') . ' ' . $fb_account->profile_name);
                            $this->info(__('Posted on Facebook Account:') . ' ' . $fb_account->profile_name);
                        } catch (FacebookSDKException $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('Unable to post on the Facebook Account') . ' "' . $fb_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            $this->error(__('Unable to post on the Facebook Account') . ' "' . $fb_account->profile_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            continue;
                        } catch (\Exception $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('The Facebook Account') . ' "' . $fb_account->profile_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $this->error(__('The Facebook Account') . ' "' . $fb_account->profile_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $fb_account->status = 'inactive';
                            $fb_account->save();
                            continue;
                        }
                    }
                }
            }

            $fb_page_ids = explode(',', $post->fb_page_ids);
            //Post to all facebook pages...
            if (isset($fb_page_ids) && count($fb_page_ids)) {
                foreach ($fb_page_ids as $fb_page_id) {
                    $fb_page = FbPage::where('status', 'active')->find($fb_page_id);
                    if ($fb_page) {
                        $credentials = json_decode($fb_page->page_credentials, true);

                        $access_token = $credentials['access_token'];

                        try {
                            // Post as media
                            if($post->fb_media == 'yes') {
                                // If image is existing, post as an image...
                                if (count($image_files)) {
                                    foreach($image_files as $image_file) {
                                        $data = ['source' => $fb->fileToUpload(public_path($image_file)),
                                            'message' => $post->msg_body];
                                        $fb->post('/' . $fb_page->page_id . '/photos', $data, $access_token);
                                    }
                                }

                                // If video is existing, share..
                                if (count($video_files)) {
                                    foreach($video_files as $video_file){
                                        $data = ['title' => $post->video_title, 'description' => $post->msg_body,
                                            'source' => $fb->videoToUpload(public_path($video_file))];
                                        $fb->post('/' . $fb_page->page_id . '/videos', $data, $access_token);
                                    }
                                }
                            }

                            // Post as Status...
                            if ($post->fb_status == 'yes') {
                                $data = ['message' => $post->msg_body];
                                $fb->post('/' . $fb_page->page_id . '/feed', $data, $access_token);
                            }

                            // Post as Link...
                            if($post->fb_link == 'yes') {
                                $data = [
                                    'link' => $post->link_url,
                                    'message' => __('Description:') . ' ' .$post->msg_body,
                                ];
                                $fb->post('/' . $fb_page->page_id . '/feed', $data,  $access_token);
                            }

                            // Log the new post...
                            $this->postLog('fb');
                            $this->logFb('success', __('Posted on Facebook Page:') . ' ' . $fb_page->page_name);
                            $this->info(__('Posted on Facebook Page:') . ' ' . $fb_page->page_name);
                        } catch (FacebookSDKException $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('Unable to post on the Facebook Page') . ' "' . $fb_page->page_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            $this->error(__('Unable to post on the Facebook Page') . ' "' . $fb_page->page_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            continue;
                        } catch (\Exception $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('The Facebook Page') . ' "' . $fb_page->page_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $this->error(__('The Facebook Page') . ' "' . $fb_page->page_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $fb_page->status = 'inactive';
                            $fb_page->save();
                            continue;
                        }
                    }
                }
            }

            $fb_group_ids = explode(',', $post->fb_group_ids);
            //Post to all facebook groups...
            if (isset($fb_group_ids) && count($fb_group_ids)) {
                foreach ($fb_group_ids as $fb_group_id) {
                    $fb_group = FbGroup::where('status', 'active')->find($fb_group_id);
                    if ($fb_group) {
                        $credentials = json_decode($fb_group->facebook_account->credentials, true);

                        $access_token = $credentials['access_token'];

                        try {
                            // Post as media
                            if($post->fb_media == 'yes') {
                                // If image is existing, post as an image...
                                if (count($image_files)) {
                                    foreach($image_files as $image_file) {
                                        $data = ['source' => $fb->fileToUpload(public_path($image_file)),
                                            'message' => $post->msg_body];
                                        $fb->post('/' . $fb_group->group_id . '/photos', $data, $access_token);
                                    }
                                }

                                // If video is existing, share..
                                if (count($video_files)) {
                                    foreach($video_files as $video_file){
                                        $data = ['title' => $post->video_title, 'description' => $post->msg_body,
                                            'source' => $fb->videoToUpload(public_path($video_file))];
                                        $fb->post('/' . $fb_group->group_id . '/videos', $data, $access_token);
                                    }
                                }
                            }

                            // Post as Status...
                            if ($post->fb_status == 'yes') {
                                $data = ['message' => $post->msg_body];
                                $fb->post('/' . $fb_group->group_id . '/feed', $data, $access_token);
                            }

                            // Post as Link...
                            if($post->fb_link == 'yes') {
                                $data = [
                                    'link' => $post->link_url,
                                    'message' => __('Description:') . ' ' .$post->msg_body,
                                ];
                                $fb->post('/' . $fb_group->group_id . '/feed', $data,  $access_token);
                            }

                            // Log the new post...
                            $this->postLog('fb');
                            $this->logFb('success', __('Posted on Facebook Group:') . ' ' . $fb_group->group_name);
                            $this->info(__('Posted on Facebook Group:') . ' ' . $fb_group->group_name);
                        } catch (FacebookSDKException $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('Unable to post on the Facebook Group') . ' "' . $fb_group->group_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            $this->error(__('Unable to post on the Facebook Group') . ' "' . $fb_group->group_name . '" ' . __('Response:') . ' ' . $e->getMessage());
                            continue;
                        } catch (\Exception $e) {
                            Log::debug($e->getMessage());

                            //Write into my local logs
                            $this->logFb('danger', __('The Facebook Group') . ' "' . $fb_group->group_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $this->error(__('The Facebook Group') . ' "' . $fb_group->group_name . '" ' . __('encountered an error while attempting to post on it! Message: ') . ' ' . $e->getMessage());
                            $fb_group->status = 'inactive';
                            $fb_group->save();
                            continue;
                        }
                    }
                }
            }

            $post->status = 'completed';
            $post->save();
            $this->line(__('Completed the execution of scheduled posts!'));
        }
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

    public function logFb($state, $message)
    {
        $my_log = new MyLog();
        $my_log->type = 'fb';
        $my_log->state = $state;
        $my_log->message = $message;
        $my_log->save();
    }

    public function logTw($state, $message)
    {
        $my_log = new MyLog();
        $my_log->type = 'tw';
        $my_log->state = $state;
        $my_log->message = $message;
        $my_log->save();
    }

    public function postLog($type)
    {
        $post_log = new PostLog();
        $post_log->type = $type;
        $post_log->save();
    }
}
