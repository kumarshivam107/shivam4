<?php

namespace App\Http\Controllers\Twitter;

use App\TwDm;
use App\TwitterAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Facades\DataTables;

class DMController extends Controller
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
            $tw_dm = $tw_account_data->dm()->whereNotIn('status', ['completed', 'failed'])->get();
            if(count($tw_dm)){
                unset($tw_accounts[$tw_account_key]);
            }
        }
        // Check for alerts...
        $alerts = array();

        if(TwDm::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(TwDm::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute.')];
        }
        Session::flash('alerts', $alerts);
        return view('twitter.dm', compact('tw_accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'twitter_account' => 'required|numeric',
            'message' => 'required|max:10000',
            'date_range'  => 'required',
        ]);
        $tw_account = TwitterAccount::whereNotIn('status', ['completed', 'failed'])->find($request->get('twitter_account'));
        if($tw_account) {
            $tw_dm = new TwDm();
            $tw_dm->twitter_id = $tw_account->id;
            $tw_dm->message = $request->get('message');

            $date = explode(' - ', $request->get('date_range'));
            if($date[0]) {
                $tw_dm->start_date = Carbon::createFromFormat('Y/m/d', $date[0]);
            }
            if($date[1]){
                $tw_dm->end_date = Carbon::createFromFormat('Y/m/d', $date[1]);
            }

            $tw_dm->status = 'active';

            $tw_dm->save();

            return redirect()->back()->with('success', __('New schedule has been created successfully!'));
        }else{
            return redirect()->back()->with('error', __('The twitter account you selected could not be found!'));
        }
    }
    public function refresh(Request $request, $id)
    {
        $tw_dm = TwDm::where('status', 'inactive')->find($id);
        if($tw_dm){
            $tw_dm->status = 'active';
            $tw_dm->save();

            return redirect()->back()->with('success', __('The dm schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The dm schedule could not be found!'));
        }
    }

    public function delete(Request $request, $id)
    {
        $tw_dm = TwDm::whereIn('status', ['active', 'failed'])->find($id);
        if($tw_dm){
            try{
                $tw_dm->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The dm schedule could not be found!'));
            }

            return redirect()->back()->with('success', __('The dm schedule has been deleted successfully!'));

        }else{
            return redirect()->back()->with('error', __('The dm schedule could not be found!'));
        }
    }
    public function getData(Request $request)
    {
        //Get all dm back data...
        $tw_dm = TwDm::all();

        return DataTables::of($tw_dm)
            ->addColumn('twitter_account', function ($data){
                return $data->twitter_account->profile_name;
            })
            ->editColumn('message', function ($data){
                return (strlen($data->message) > 50)? substr($data->message, 0,50).'...': $data->message;
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
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/dm/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/twitter/dm/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->removeColumn('id')
            ->removeColumn('twitter_id')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
