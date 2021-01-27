<?php

namespace App\Http\Controllers\Twitter;

use App\IgFollowBack;
use App\TwFollowBack;
use App\TwitterAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Facades\DataTables;

class FollowBackController extends Controller
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
            $tw_follow_back = $tw_account_data->follow_back()->whereNotIn('status', ['completed', 'failed'])->get();
            if(count($tw_follow_back)){
                unset($tw_accounts[$tw_account_key]);
            }
        }

        // Check for alerts...
        $alerts = array();

        if(TwFollowBack::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(TwFollowBack::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute.')];
        }
        Session::flash('alerts', $alerts);
        return view('twitter.follow_back', compact('tw_accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'twitter_account' => 'required|numeric',
            'date_range'  => 'required',
        ]);

        $tw_account = TwitterAccount::whereNotIn('status', ['completed', 'failed'])->find($request->get('twitter_account'));
        if($tw_account){
            //Insert new data in to tw_follow_backs...
            $tw_follow_back = new TwFollowBack();
            $tw_follow_back->twitter_id = $tw_account->id;

            $date = explode(' - ', $request->get('date_range'));
            if($date[0]) {
                $tw_follow_back->start_date = Carbon::createFromFormat('Y/m/d', $date[0]);
            }
            if($date[1]){
                $tw_follow_back->end_date = Carbon::createFromFormat('Y/m/d', $date[1]);
            }

            $tw_follow_back->status = 'active';
            if($request->has('exception') && count($request->get('exception'))){
                $tw_follow_back->exception = implode(',', $request->get('exception'));
            }

            $tw_follow_back->exclude_verified = ($request->filled('exclude_verified'))? 'yes': 'no';
            $tw_follow_back->exclude_non_verified = ($request->filled('exclude_non_verified'))? 'yes': 'no';

            $tw_follow_back->save();

            return redirect()->back()->with('success', __('New schedule has been created successfully!'));
        }else{
            return redirect()->back()->with('error', __('The twitter account you selected could not be found!'));
        }
    }

    public function refresh(Request $request, $id)
    {
        $tw_follow_back = TwFollowBack::where('status', 'inactive')->find($id);
        if($tw_follow_back){
            $tw_follow_back->status = 'active';
            $tw_follow_back->save();

            return redirect()->back()->with('success', __('The follow-back schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The follow-back schedule could not be found!'));
        }
    }

    public function delete(Request $request, $id)
    {
        $tw_follow_back = TwFollowBack::whereIn('status', ['active', 'failed'])->find($id);
        if($tw_follow_back){
            try{
                $tw_follow_back->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The follow-back schedule could not be found!'));
            }

            return redirect()->back()->with('success', __('The follow-back schedule has been deleted successfully!'));

        }else{
            return redirect()->back()->with('error', __('The follow-back schedule could not be found!'));
        }
    }

    public function getData(Request $request)
    {
        //Get all follow back data...
        $tw_follow_back = TwFollowBack::all();

        return DataTables::of($tw_follow_back)
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
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/follow-back/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/follow-back/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
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
