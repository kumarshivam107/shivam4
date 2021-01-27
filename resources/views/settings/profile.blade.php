@extends('layouts.master')
@section('page_title', __('Settings'))

@section('content')
    <section class="content-header">
        <h1>
            {{__('Profile')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Profile Settings')}}</h4>
            <p>{{__('You may edit your profile details from here.')}}</p>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title">
                            <span>{{__('Edit Profile')}}</span>
                        </div>
                    </div>
                    <div class="box-body form">
                        <form role="form" method="post" action="{{route('update-profile')}}">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}">
                                                <label for="form_control_1">{{__('Display Name')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}">
                                                <label for="form_control_1">{{__('Email')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="password" class="form-control" name="old_password">
                                                <label for="form_control_1">{{__('Old Password')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="password" class="form-control" name="password">
                                                <label for="form_control_1">{{__('Password')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="password" class="form-control" name="password_confirmation">
                                                <label for="form_control_1">{{__('Verify Password')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions noborder">
                                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                <button type="reset" class="btn default">{{__('Reset')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/raphael-min.js" type="text/javascript"></script>
@endpush