<?php

namespace App\Http\Controllers\Facebook;

use App\FacebookAccount;
use App\FbGroup;
use App\FbPage;
use App\PostLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fb_accounts = FacebookAccount::paginate(3);

        $post_count = PostLog::where('type', 'fb')->count();
        $group_count = FbGroup::count();
        $page_count = FbPage::count();

        return view('facebook.accounts',
            compact(
                'fb_accounts',
                'post_count',
                'group_count',
                'page_count'
            ));
    }
}
