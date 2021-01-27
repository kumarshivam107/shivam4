@extends('layouts.master')
@section('page_title', __('Settings'))

@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h1 class="page-title"> {{__('Global')}} </h1>
        <!-- END PAGE HEADER-->
        <div class="m-heading-1 border-blue m-bordered">
            <h3>{{__('Site Settings')}}</h3>
            <p>{{__('You may edit your site details from here.')}}</p>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">{{__('Edit Settings')}}</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" method="post" action="{{LaravelLocalization::getLocalizedURL(null, '/update-site', [])}}">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="name" value="">
                                                <label for="form_control_1">{{__('Site Name')}}</label>
                                                <i class="fa fa-globe"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions noborder">
                                <button type="submit" class="btn blue">{{__('Submit')}}</button>
                                <button type="reset" class="btn default">{{__('Reset')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/raphael-min.js" type="text/javascript"></script>
@endpush