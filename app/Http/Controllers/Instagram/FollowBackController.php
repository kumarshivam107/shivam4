<?php

namespace App\Http\Controllers\Instagram;

use App\IgFollowBack;
use App\InstagramAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
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
        // Select all instagram account that hasn't been scheduled yet...
        $ig_accounts = InstagramAccount::all();
        foreach($ig_accounts as $ig_account_key => $ig_account_data){
            $ig_follow_back = $ig_account_data->follow_back()->whereNotIn('status', ['completed', 'failed'])->get();
            if(count($ig_follow_back)){
                unset($ig_accounts[$ig_account_key]);
            }
        }

        // Check for alerts...
        $alerts = array();

        if(IgFollowBack::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(IgFollowBack::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute.')];
        }
        Session::flash('alerts', $alerts);
        return view('instagram.follow_back', compact('ig_accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'instagram_account' => 'required|numeric',
            'date_range'  => 'required',
        ]);

        $ig_account = InstagramAccount::whereNotIn('status', ['completed', 'failed'])->find($request->get('instagram_account'));
        if($ig_account){
            //Insert new data in to ig_follow_backs...
            $ig_follow_back = new IgFollowBack();
            $ig_follow_back->instagram_id = $ig_account->id;

            $date = explode(' - ', $request->get('date_range'));
            if($date[0]) {
                $ig_follow_back->start_date = Carbon::createFromFormat('Y/m/d', $date[0]);
            }
            if($date[1]){
                $ig_follow_back->end_date = Carbon::createFromFormat('Y/m/d', $date[1]);
            }

            $ig_follow_back->status = 'active';
            if($request->has('exception') && count($request->get('exception'))){
                $ig_follow_back->exception = implode(',', $request->get('exception'));
            }

            $ig_follow_back->exclude_verified = ($request->filled('exclude_verified'))? 'yes': 'no';
            $ig_follow_back->exclude_non_verified = ($request->filled('exclude_non_verified'))? 'yes': 'no';

            $ig_follow_back->save();

            return redirect()->back()->with('success', __('New schedule has been created successfully!'));
        }else{
            return redirect()->back()->with('error', __('The instagram account you selected could not be found!'));
        }
    }

    public function refresh(Request $request, $id)
    {
        $ig_follow_back = IgFollowBack::where('status', 'inactive')->find($id);
        if($ig_follow_back){
            $ig_follow_back->status = 'active';
            $ig_follow_back->save();

            return redirect()->back()->with('success', __('The follow-back schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The follow-back schedule could not be found!'));
        }
    }

    public function delete(Request $request, $id)
    {
        $ig_follow_back = IgFollowBack::whereIn('status', ['active', 'failed'])->find($id);
        if($ig_follow_back){
            try{
                $ig_follow_back->delete();
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
        $ig_follow_back = IgFollowBack::all();

        return DataTables::of($ig_follow_back)
            ->addColumn('instagram_account', function ($data){
                return $data->instagram_account->profile_name;
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
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/instagram/follow-back/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/instagram/follow-back/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
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
            ->removeColumn('instagram_id')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'status', 'exclude_verified', 'exclude_non_verified'])
            ->make(true);
    }
}
