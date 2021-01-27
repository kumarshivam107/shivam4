<?php

namespace App\Http\Controllers\Facebook;

use App\FbGroup;
use App\FbPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Facades\DataTables;

class PageAndGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('facebook.page-group');
    }

    public function refreshPage($id)
    {
        $fb_page = FbPage::find($id);
        if($fb_page){
            $fb_page->status = 'active';
            $fb_page->save();

            return redirect()->back()->with('success', __('The Facebook Page has been refreshed successfully!'));
        }else{
            return redirect()->back()->with('error', __('The Facebook Page could not be found!'));
        }
    }

    public function deletePage($id)
    {
        $fb_page = FbPage::find($id);
        if($fb_page){
            try{
                $fb_page->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The Facebook Page could not be found!'));
            }

            return redirect()->back()->with('success', __('The Facebook Page has been deleted successfully!'));
        }else{
            return redirect()->back()->with('error', __('The Facebook Page could not be found!'));
        }
    }

    public function refreshGroup($id)
    {
        $fb_group = FbGroup::find($id);
        if($fb_group){
            $fb_group->status = 'active';
            $fb_group->save();

            return redirect()->back()->with('success', __('The Facebook Group has been refreshed successfully!'));
        }else{
            return redirect()->back()->with('error', __('The Facebook Group could not be found!'));
        }
    }

    public function deleteGroup($id)
    {
        $fb_group = FbGroup::find($id);
        if($fb_group){
            try{
                $fb_group->delete();
            }catch(\Exception $e){
                return redirect()->back()->with('error', __('The Facebook Group could not be found!'));
            }

            return redirect()->back()->with('success', __('The Facebook Group has been deleted successfully!'));
        }else{
            return redirect()->back()->with('error', __('The Facebook Group could not be found!'));
        }
    }

    public function getPageData()
    {
        $fb_pages = FbPage::all();

        return DataTables::of($fb_pages)
            ->addColumn('facebook_account', function ($data){
                return $data->facebook_account->profile_name;
            })
            ->editColumn('status', function ($data){
                $state = 'default';
                switch($data->status){
                    case 'active':
                        $state = 'info';
                        break;
                    case 'inactive':
                        $state  = 'danger';
                        break;
                }
                return '<span class="label label-sm label-'.$state.'"> '.ucfirst($data->status).' </span>';

            })
            ->addColumn('action', function ($data){
                $action = '';
                if($data->status == 'inactive') {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/facebook/page/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if($data->status == 'active') {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/facebook/page/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->removeColumn('id')
            ->removeColumn('facebook_id')
            ->removeColumn('page_id')
            ->removeColumn('page_credentials')
            ->removeColumn('created_at')
            ->removeColumn('updates_at')
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getGroupData()
    {
        $fb_groups = FbGroup::all();

        return DataTables::of($fb_groups)
            ->addColumn('facebook_account', function ($data){
                return $data->facebook_account->profile_name;
            })
            ->editColumn('status', function ($data){
                $state = 'default';
                switch($data->status){
                    case 'active':
                        $state = 'info';
                        break;
                    case 'inactive':
                        $state  = 'danger';
                        break;
                }
                return '<span class="label label-sm label-'.$state.'"> '.ucfirst($data->status).' </span>';

            })
            ->addColumn('action', function ($data){
                $action = '';
                if($data->status == 'inactive') {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/facebook/group/'.$data->id.'/refresh', []).'" class="btn btn-circle btn-primary" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-refresh"></i>
                        </a>';
                }
                if($data->status == 'active') {
                    $action .= '
                        <a href="'.LaravelLocalization::getLocalizedURL(null, '/facebook/group/'.$data->id.'/delete', []).'" class="btn btn-circle btn-danger" data-toggle="confirmation" data-singleton="true">
                            <i class="fa fa-trash"></i>
                        </a>
                        ';
                }
                return $action;
            })
            ->removeColumn('id')
            ->removeColumn('facebook_id')
            ->removeColumn('page_id')
            ->removeColumn('page_credentials')
            ->removeColumn('created_at')
            ->removeColumn('updates_at')
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
