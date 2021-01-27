@extends('layouts.master')
@section('page_title', __('Settings'))

@section('content')
    <section class="content-header">
        <h1>
            {{__('Twitter API')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Setup Twitter API')}}</h4>
            <p>{{__('Refer to the documentation for a comprehensive walk-through on how to create an app on twitter. Below are the required your site-specific requirement for creating the Twitter APP.')}}</p>
            <div>
                <b>{{__('Redirect Url')}}</b>
                <ul>
                    <li>{{__('Site Domain')}}: <b>{{url('/')}}</b></li>
                    <li>{{__('Valid OAth Redirect Url')}}: <b>{{url('twitter/callback')}}</b></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title">
                            <span>{{__('Twitter API')}}</span>
                            @if(isset($api_status))
                                <span class="label label-{{($api_status == 'Active')? 'success':'danger'}}"> {{$api_status}} </span>
                            @endif
                        </div>
                    </div>
                    <div class="box-body form">
                        <form role="form" method="post">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="tw_access_token" value="{{DotenvEditor::getValue('TWITTER_ACCESS_TOKEN')}}">
                                                <label for="form_control_1">{{__('Twitter Access Token')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="tw_access_token_secret" value="{{DotenvEditor::getValue('TWITTER_ACCESS_TOKEN_SECRET')}}">
                                                <label for="form_control_1">{{__('Twitter Access Token Secret')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="tw_consumer_key" value="{{DotenvEditor::getValue('TWITTER_CONSUMER_KEY')}}">
                                                <label for="form_control_1">{{__('Twitter Consumer Key')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="tw_consumer_secret" value="{{DotenvEditor::getValue('TWITTER_CONSUMER_SECRET')}}">
                                                <label for="form_control_1">{{__('Twitter Consumer Secret')}}</label>
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