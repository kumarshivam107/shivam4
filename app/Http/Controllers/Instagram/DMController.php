<?php

namespace App\Http\Controllers\Instagram;

use App\IgDm;
use App\InstagramAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
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
        // Select all instagram account that hasn't been scheduled yet...
        $ig_accounts = InstagramAccount::all();
        foreach($ig_accounts as $ig_account_key => $ig_account_data){
            $ig_dm = $ig_account_data->dm()->whereNotIn('status', ['completed', 'failed'])->get();
            if(count($ig_dm)){
                unset($ig_accounts[$ig_account_key]);
            }
        }

        // Check for alerts...
        $alerts = array();

        if(IgDm::where('status', 'inactive')->exists()){
            $alerts[] = ['type' => 'warning', 'msg' => __('Some of your schedules is Inactive. Check the Log and click the Refresh button to proceed.')];
        }
        if(IgDm::where('status', 'failed')->exists()){
            $alerts[] = ['type' => 'danger', 'msg' => __('Some of your schedules Failed to execute.')];
        }
        Session::flash('alerts', $alerts);
        return view('instagram.dm', compact('ig_accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'instagram_account' => 'required|numeric',
            'message' => 'required|max:450',
            'date_range'  => 'required',
        ]);
        $ig_account = InstagramAccount::whereNotIn('status', ['completed', 'failed'])->find($request->get('instagram_account'));
        if($ig_account) {
            $ig_dm = new IgDm();
            $ig_dm->instagram_id = $ig_account->id;
            $ig_dm->message = $request->get('message');

            $date = explode(' - ', $request->get('date_range'));
            if($date[0]) {
                $ig_dm->start_date = Carbon::createFromFormat('Y/m/d', $date[0]);
            }
            if($date[1]){
                $ig_dm->end_date = Carbon::createFromFormat('Y/m/d', $date[1]);
            }

            $ig_dm->status = 'active';
            $ig_dm->save();

            return redirect()->back()->with('success', __('New schedule has been created successfully!'));
        }else{
            return redirect()->back()->with('error', __('The instagram account you selected could not be found!'));
        }
    }
    public function refresh(Request $request, $id)
    {
        $ig_dm = IgDm::where('status', 'inactive')->find($id);
        if($ig_dm){
            $ig_dm->status = 'active';
            $ig_dm->save();

            return redirect()->back()->with('success', __('The dm schedule has been refreshed successfully!'));

        }else{
            return redirect()->back()->with('error', __('The dm schedule could not be found!'));
        }
    }

    public function delete(Request $request, $id)
    {
        $ig_dm = IgDm::whereIn('status', ['active', 'failed'])->find($id);
        if($ig_dm){
            try{
                $ig_dm->delete();
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
        $ig_dm = IgDm::all();

        return DataTables::of($ig_dm)
            ->addColumn('instagram_account', function ($data){
                return $data->instagram_account->profile_name;
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
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/instagram/dm/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if(in_array($data->status, ['active', 'failed'])) {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/instagram/dm/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->removeColumn('id')
            ->removeColumn('instagram_id')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
