<?php

namespace App\Http\Controllers\General;

use App\FacebookAccount;
use App\FbGroup;
use App\FbPage;
use App\InstagramAccount;
use App\MyLog;
use App\PostLog;
use App\PostQueue;
use App\TwitterAccount;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\UploadFailedException;
use InstagramAPI\Instagram;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Thujohn\Twitter\Facades\Twitter;
use Yajra\DataTables\Facades\DataTables;

class SchedulePostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ig_accounts = InstagramAccount::where('status', 'active')->get();
        $fb_accounts = FacebookAccount::where('status', 'active')->get();
        $tw_accounts = TwitterAccount::where('status', 'active')->get();
        $fb_groups = FbGroup::where('status', 'active')->get();
        $fb_pages = FbPage::where('status', 'active')->get();

        // Check for alerts...
        $alerts = array();

        if(PostQueue::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(PostQueue::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute. This is usually due to missing files from the File Manager which may have been added to schedule previously.')];
        }
        Session::flash('alerts', $alerts);
        return view('general.schedule_posts', compact('ig_accounts', 'fb_accounts', 'tw_accounts',
            'fb_groups', 'fb_pages'));
    }

    public function store(Request $request, Instagram $ig, LaravelFacebookSdk $fb)
    {
        $this->validate($request, [
            'message' => 'required|max:5000',
        ]);

        // Remove empty image fields...
        $image_paths = $request->get('image_path');
        foreach ($image_paths as $key => $value){
            if(!$value || empty($value)){
                unset($image_paths[$key]);
            }
        }

        //Remove empty video fields...
        $video_paths = $request->get('video_path');
        foreach ($video_paths as $key => $value){
            if(!$value || empty($value)){
                unset($video_paths[$key]);
            }
        }

        // Validate input fields for instagram
        if($request->has('instagram_ids') && count($request->get('instagram_ids'))){
            $this->validate($request, [
                'image_path' => 'required|array|min:1|max:10',
                'message' => 'required|max:2000'
            ],[
                'image_path.required' => __('At least, one image attachment is required to post on Instagram!'),
                'message.max' => __('Maximum character allowed for message on Instagram is :max')
            ]);

            if(!count($image_paths)){
                return redirect()->back()
                    ->with('error', __('At least, one image attachment is required to post on Instagram!'))
                    ->withInput($request->all());
            }

            // Posting as single requires Maximum of 3 Image uploads...
            if($request->filled('ig_single')){
                $this->validate($request, [
                    'image_path' => 'required|array|min:1|max:3'
                ],[
                    'image_path.max' => __('Posting as single on Instagram requires a maximum of :max images. Remove some of the images or disable posting as single under Instagram options.')
                ]);
            }

            // Posting as story requires Minimum of 2 Image uploads
            if($request->filled('ig_story')){
                $this->validate($request, [
                    'image_path' => 'required|array|min:2|max:10'
                ],[
                    'image_path.min' => __('Posting as album on Instagram requires a minimum of :min images. Add some images more or disable posting as album under Instagram options.')
                ]);
            }
        }

        // Validate input fields for twitter
        if($request->has('twitter_ids') && count($request->get('twitter_ids'))) {
            $this->validate($request, [
                'message' => 'required|max:250'
            ],[
                'message.max' => __('Maximum character allowed for message on Twitter is :max')
            ]);
        }

        // Validate input fields for facebook
        if($request->filled('facebook_ids') || $request->filled('fb_page_ids') || $request->filled('fb_group_ids')){
            if($request->filled('fb_link')){
                $this->validate($request, [
                    'link_url' => 'required|url|min:5'
                ],[
                    'link_url.required' => 'A link url is required, in order to post as link on Facebook.'
                ]);
            }
        }

        // If this is a schedule, validate schedule_date...
        if(!$request->has('post_now')){
            $this->validate($request, [
                'schedule_date' => 'required|filled',
            ]);

            if(!($schedule_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule_date')))){
                return redirect()->back()
                    ->with('error', __('The scheduled date is invalid, Please try again.'))
                    ->withInput($request->all());
            }
        }

        // Validate Images...
        if(count($image_paths)){
            foreach($image_paths as $image_path){
                if ($image_path && File::exists(public_path($image_path))) {
                    // Make sure that this is actually an image...
                    $allowed_types = ['image/jpeg','image/jpeg','image/png'];
                    $mime_type = mime_content_type(public_path($image_path));

                    if(!in_array($mime_type, $allowed_types)){
                        return redirect()->back()
                            ->with('error', __('The image file type you provided is not allowed! Allowed types are jpeg, jpg, png.'))
                            ->withInput($request->all());
                    }

                    // Validate Image Aspect Ratio for instagram...
                    if ($request->has('instagram_ids') && !empty($request->get('instagram_ids'))) {
                        if (list($width, $height, $type, $attr) = getimagesize(public_path($image_path))) {
                            /*
                            if (($width / max($height, 1)) != 1) {
                                return redirect()->back()
                                    ->with('error', __('Your image attachment has to be of equal width and height, before it can be posted on instagram!'))
                                    ->withInput($request->all());
                            }
                            */

                            if($width > 1080 || $width < 320 || $height > 1080 || $height < 320){
                                return redirect()->back()
                                    ->with('error', __('The width and height has to be between 320px and 1080px! This is one of Instagram\'s T&C'))
                                    ->withInput($request->all());
                            }
                        }
                    }
                }
            }

            // Validate maximum allowed images for Facebook
            if($request->hasAny(['facebook_ids', 'fb_page_ids', 'fb_group_ids'])) {
                if ($request->filled('fb_media')) {
                    $this->validate($request, [
                        'image_path' => 'required|array|max:3',
                    ], [
                        'image_path.max' => __('Posting as media on Facebook requires a maximum of :max images. Remove some of the images or disable posting as media under Facebook options.')
                    ]);
                }
            }

            // Validate maximum allowed images for Twitter
            if($request->has('twitter_ids') && count($request->get('twitter_ids'))) {
                if ($request->filled('tw_media')) {
                    $this->validate($request, [
                        'image_path' => 'required|array|max:2',
                    ], [
                        'image_path.max' => __('Posting as media on Twitter requires a maximum of :max images. Remove some of the images or disable posting as media under Twitter options.')
                    ]);
                }
            }
        }


        // Validate Videos...
        if(count($video_paths)) {
            foreach($video_paths as $video_path){
                if ($video_path && File::exists(public_path($video_path))) {
                    // Make sure that this is actually an image...
                    $allowed_types = ['video/mp4', 'video/3gpp', 'video/3gpp2'];
                    $mime_type = mime_content_type(public_path($video_path));

                    if (!in_array($mime_type, $allowed_types)) {
                        return redirect()->back()
                            ->with('error', __('The video file type you provided is not allowed! Allowed types are mp4 and 3gp.'))
                            ->withInput($request->all());
                    }
                }
            }

            // If posting as media is switched on for facebook, make sure the video title is required...
            if($request->filled('facebook_ids') || $request->filled('fb_page_ids') || $request->filled('fb_group_ids')) {
                if ($request->filled('fb_media')) {
                    $this->validate($request, [
                        'video_title' => 'required|min:5',
                        'video_path' => 'required|array|max:3',
                    ], [
                        'video_title.required' => 'A video title is required, in order to post as media on Facebook.',
                        'video_path.max' => 'Posting as media on facebook requires a maximum of :max videos. Remove some videos or disable posting as media in Facebook options.'
                    ]);
                }
            }
        }


        // Get all instagram ids
        if($request->has('instagram_ids') && count($request->get('instagram_ids'))) {
            $instagram_ids = $request->get('instagram_ids');
            foreach ($instagram_ids as $instagram_id_key => $instagram_id_data) {
                $ig_check = InstagramAccount::find($instagram_id_data);
                if (!$ig_check) {
                    unset($instagram_ids[$instagram_id_key]);
                }
            }
        }

        // Get all facebook ids
        if($request->has('facebook_ids') && count($request->get('facebook_ids'))) {
            $facebook_ids = $request->get('facebook_ids');
            foreach ($facebook_ids as $facebook_id_key => $facebook_id_data) {
                $fb_check = FacebookAccount::find($facebook_id_data);
                if (!$fb_check) {
                    unset($facebook_ids[$facebook_id_key]);
                }
            }
        }
        
        if($request->has('fb_page_ids') && count($request->get('fb_page_ids'))) {
            // Get all facebook page ids
            $fb_page_ids = $request->get('fb_page_ids');
            foreach ($fb_page_ids as $fb_page_id_key => $fb_page_id_data) {
                $fb_check = FbPage::find($fb_page_id_data);
                if (!$fb_check) {
                    unset($fb_page_ids[$fb_page_id_key]);
                }
            }
        }

        if($request->has('fb_group_ids') && count($request->get('fb_group_ids'))) {
            // Get all facebook group ids
            $fb_group_ids = $request->get('fb_group_ids');
            foreach ($fb_group_ids as $fb_group_id_key => $fb_group_id_data) {
                $fb_check = FbGroup::find($fb_group_id_data);
                if (!$fb_check) {
                    unset($fb_group_ids[$fb_group_id_key]);
                }
            }
        }

        if($request->has('twitter_ids') && count($request->get('twitter_ids'))) {
            // Get all twitter ids
            $twitter_ids = $request->get('twitter_ids');
            foreach ($twitter_ids as $twitter_id_key => $twitter_id_data) {
                $tw_check = TwitterAccount::find($twitter_id_data);
                if (!$tw_check) {
                    unset($twitter_ids[$twitter_id_key]);
                }
            }
        }

        // Insert to queue...
        $post_queue = new PostQueue();
        $post_queue->msg_body = $request->get('message');
        if(isset($instagram_ids) && count($instagram_ids)) {
            $post_queue->instagram_ids = implode(',', $instagram_ids);
        }
        if(isset($facebook_ids) && count($facebook_ids)) {
            $post_queue->facebook_ids = implode(',', $facebook_ids);
        }
        if(isset($twitter_ids) && count($twitter_ids)) {
            $post_queue->twitter_ids = implode(',', $twitter_ids);
        }
        if(isset($fb_group_ids) && count($fb_group_ids)) {
            $post_queue->fb_group_ids = implode(',', $fb_group_ids);
        }
        if(isset($fb_page_ids) && count($fb_page_ids)) {
            $post_queue->fb_page_ids = implode(',', $fb_page_ids);
        }
        if(!$request->has('post_now') && isset($schedule_date)){
            $post_queue->schedule_time = $schedule_date->format('Y-m-d H:i:s');
        }else{
            $post_queue->schedule_time = Carbon::now()->format('Y-m-d H:i:s');
        }
        if(isset($image_paths) && count($image_paths)) {
            $post_queue->image_file = implode(',', $image_paths);
        }
        if(isset($video_paths) && count($video_paths)) {
            $post_queue->video_file = implode(',', $video_paths);
        }
        $post_queue->ig_single = ($request->filled('ig_single'))? 'yes':'no';
        $post_queue->ig_story = ($request->filled('ig_story'))? 'yes':'no';
        $post_queue->tw_status = ($request->filled('tw_status'))? 'yes':'no';
        $post_queue->tw_media = ($request->filled('tw_media'))? 'yes':'no';
        $post_queue->fb_status = ($request->filled('fb_status'))? 'yes':'no';
        $post_queue->fb_media = ($request->filled('fb_media'))? 'yes':'no';
        $post_queue->fb_link = ($request->filled('fb_link'))? 'yes':'no';
        $post_queue->video_title = $request->get('video_title');
        $post_queue->link_url = $request->get('link_url');
        $post_queue->status = 'active';
        $post_queue->save();

        return redirect()->back()->with('success', __('Schedule has been added successfully!'));
    }

    public function refresh($id)
    {
        $post_queue = PostQueue::where('status', 'inactive')->find($id);
        if($post_queue){
            $post_queue->status = 'active';
            $post_queue->save();

            return redirect()->back()->with('success', __('The post schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The post schedule could not be found!'));
        }
    }
    
    public function delete($id)
    {
        $post_queue = PostQueue::whereIn('status', ['active', 'failed'])->find($id);
        if($post_queue){
            try{
                $post_queue->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The post schedule could not be found!'));
            }

            return redirect()->back()->with('success', __('The post schedule has been deleted successfully!'));

        }else {
            return redirect()->back()->with('error', __('The post schedule could not be found!'));
        }
    }
    
    public function getData()
    {
        $post_queue = PostQueue::all();

        return DataTables::of($post_queue)
            ->editColumn('msg_body', function ($data){
                return (strlen($data->msg_body) > 50)? substr($data->msg_body, 0,50).'...': $data->msg_body;
            })
            ->editColumn('instagram_ids', function ($data){
                $profile_names = array();
                $ids = explode(',', $data->instagram_ids);
                foreach($ids as $id){
                    $ig_account = InstagramAccount::find($id);
                    if($ig_account){
                        array_push($profile_names, $ig_account->profile_name);
                    }
                }
                return implode(' & ', $profile_names);
            })
            ->editColumn('twitter_ids', function ($data){
                $profile_names = array();
                $ids = explode(',', $data->twitter_ids);
                foreach($ids as $id){
                    $tw_account = TwitterAccount::find($id);
                    if($tw_account){
                        array_push($profile_names, $tw_account->profile_name);
                    }
                }
                return implode(' & ', $profile_names);
            })
            ->editColumn('facebook_ids', function ($data){
                $profile_names = array();
                $ids = explode(',', $data->facebook_ids);
                foreach($ids as $id){
                    $fb_account = FacebookAccount::find($id);
                    if($fb_account){
                        array_push($profile_names, $fb_account->profile_name);
                    }
                }
                return implode(' & ', $profile_names);
            })
            ->editColumn('fb_group_ids', function ($data){
                $profile_names = array();
                $ids = explode(',', $data->fb_group_ids);
                foreach($ids as $id){
                    $account = FbGroup::find($id);
                    if($account){
                        array_push($profile_names, $account->group_name);
                    }
                }
                return implode(' & ', $profile_names);
            })
            ->editColumn('fb_page_ids', function ($data){
                $profile_names = array();
                $ids = explode(',', $data->fb_page_ids);
                foreach($ids as $id){
                    $account = FbPage::find($id);
                    if($account){
                        array_push($profile_names, $account->page_name);
                    }
                }
                return implode(' & ', $profile_names);
            })
            ->editColumn('schedule_time', function ($data){
                $start_date = new Carbon($data->schedule_time);
                return $start_date->toFormattedDateString();
            })
            ->editColumn('status', function ($data){
                $state = 'default';
                switch($data->status){
                    case 'active':
                        $state = 'info';
                        break;
                    case 'inactive':
                        $state  = 'warning';
                        break;
                    case 'completed':
                        $state = 'success';
                        break;
                    case 'failed':
                        $state = 'danger';
                        break;
                }
                return '<span class="label label-sm label-'.$state.'"> '.ucfirst($data->status).' </span>';

            })
            ->addColumn('action', function ($data){
                $action = '';
                if($data->status == 'inactive') {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/schedule/post/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/schedule/post/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->removeColumn('id')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'status'])
            ->make(true);
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
