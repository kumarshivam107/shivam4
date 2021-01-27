<?php

namespace App\Http\Controllers\Dashboard;

use App\DmLog;
use App\FollowBackLog;
use App\MyLog;
use App\PostLog;
use App\UnfollowLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get post statistics...
        $fb_post_stat = PostLog::where('type', 'fb')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        $ig_post_stat = PostLog::where('type', 'ig')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        $tw_post_stat = PostLog::where('type', 'tw')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        $post_count = PostLog::count();
        $dm_count = DmLog::count();
        $follow_back_count = FollowBackLog::count();
        $unfollow_count = UnfollowLog::count();

        $logs = MyLog::orderBy('created_at', 'DESC')->paginate(20);

        return view('dashboard.statistics',
            compact(
                'fb_post_stat',
                'ig_post_stat',
                'tw_post_stat',
                'post_count',
                'dm_count',
                'follow_back_count',
                'unfollow_count',
                'logs'
            ));
    }

    public function clearLog()
    {
        try{
            MyLog::truncate();
        }catch(\Exception $e){
            return redirect()->back()->with('status', __('We were unable to clear Logs record!'));
        }
        return redirect()->back()->with('status', __('Log records cleared successfully!'));
    }
}
