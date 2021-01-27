<?php

namespace App\Http\Controllers\Twitter;

use App\TwDm;
use App\TwFollowBack;
use App\TwitterAccount;
use App\TwUnfollow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Facades\DataTables;

class UnfollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Select all twitter account that hasn't been scheduled yet...
        $tw_accounts = TwitterAccount::all();
        foreach($tw_accounts as $tw_account_key => $tw_account_data){
            $tw_unfollow = $tw_account_data->unfollow()->whereNotIn('status', ['completed', 'failed'])->get();
            if(count($tw_unfollow)){
                unset($tw_accounts[$tw_account_key]);
            }
        }

        // Check for alerts...
        $alerts = array();

        if(TwUnfollow::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(TwUnfollow::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute.')];
        }
        Session::flash('alerts', $alerts);
        return view('twitter.unfollow', compact('tw_accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'twitter_account' => 'required|numeric',
            'date_range'  => 'required',
        ]);
        $tw_account = TwitterAccount::whereNotIn('status', ['completed', 'failed'])->find($request->get('twitter_account'));
        if($tw_account) {
            $tw_unfollow = new TwUnfollow();
            $tw_unfollow->twitter_id = $tw_account->id;

            $date = explode(' - ', $request->get('date_range'));
            if($date[0]) {
                $tw_unfollow->start_date = Carbon::createFromFormat('Y/m/d', $date[0]);
            }
            if($date[1]){
                $tw_unfollow->end_date = Carbon::createFromFormat('Y/m/d', $date[1]);
            }

            $tw_unfollow->status = 'active';
            if($request->has('exception') && count($request->get('exception'))){
                $tw_unfollow->exception = implode(',', $request->get('exception'));
            }

            $tw_unfollow->exclude_verified = ($request->filled('exclude_verified'))? 'yes': 'no';
            $tw_unfollow->exclude_non_verified = ($request->filled('exclude_non_verified'))? 'yes': 'no';

            $tw_unfollow->save();

            return redirect()->back()->with('success', __('New schedule has been created successfully!'));
        }else{
            return redirect()->back()->with('error', __('The twitter account you selected could not be found!'));
        }
    }
    public function refresh(Request $request, $id)
    {
        $tw_unfollow = TwUnfollow::where('status', 'inactive')->find($id);
        if($tw_unfollow){
            $tw_unfollow->status = 'active';
            $tw_unfollow->save();

            return redirect()->back()->with('success', __('The unfollow schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The unfollow schedule could not be found!'));
        }
    }

    public function delete(Request $request, $id)
    {
        $tw_unfollow = TwUnfollow::whereIn('status', ['active', 'failed'])->find($id);
        if($tw_unfollow){
            try{
                $tw_unfollow->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The unfollow schedule could not be found!'));
            }

            return redirect()->back()->with('success', __('The unfollow schedule has been deleted successfully!'));

        }else{
            return redirect()->back()->with('error', __('The unfollow schedule could not be found!'));
        }
    }

    public function getData(Request $request)
    {
        //Get all follow back data...
        $tw_unfollow = TwUnfollow::all();

        return DataTables::of($tw_unfollow)
            ->addColumn('twitter_account', function ($data){
                return $data->twitter_account->profile_name;
            })
            ->editColumn('start_date', function ($data){
                $start_date = new Carbon($data->start_date);
                return $start_date->toFormattedDateString();
            })
            ->editColumn('end_date', function ($data){
                $end_date = new Carbon($data->end_date);
                return $end_date->toFormattedDateString();
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
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/unfollow/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/unfollow/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->editColumn('exclude_verified', function($data){
                return '<span class="label label-sm label-default"> '.ucfirst($data->exclude_verified).' </span>';
            })
            ->editColumn('exclude_non_verified', function($data){
                return '<span class="label label-sm label-default"> '.ucfirst($data->exclude_non_verified).' </span>';
            })
            ->removeColumn('exception')
            ->removeColumn('id')
            ->removeColumn('twitter_id')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'status', 'exclude_verified', 'exclude_non_verified'])
            ->make(true);
    }
}
