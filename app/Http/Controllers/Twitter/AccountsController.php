<?php

namespace App\Http\Controllers\Twitter;

use App\DmLog;
use App\FollowBackLog;
use App\PostLog;
use App\TwitterAccount;
use App\UnfollowLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tw_accounts = TwitterAccount::paginate(3);

        $post_count = PostLog::where('type', 'tw')->count();
        $dm_count = DmLog::where('type', 'tw')->count();
        $follow_back_count = FollowBackLog::where('type', 'tw')->count();
        $unfollow_count = UnfollowLog::where('type', 'tw')->count();

        $follow_back_stat = FollowBackLog::where('type', 'tw')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        $unfollow_stat = UnfollowLog::where('type', 'tw')
            ->select(DB::raw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURDATE())'))
            ->groupBy('month', 'year')
            ->get();

        return view('twitter.accounts',
            compact(
                'tw_accounts',
                'post_count',
                'dm_count',
                'follow_back_count',
                'unfollow_count',
                'follow_back_stat',
                'unfollow_stat'
            ));
    }
}
