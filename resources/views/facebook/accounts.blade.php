@extends('layouts.master')
@section('page_title', __('Facebook'))

@section('content')
    <section class="content-header">
        <h1>
            {{__('My Accounts')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Facebook Statistics')}}</h4>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{number_format($post_count)}}</h3>
                        <p>{{__('Posts')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-tags"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{number_format($page_count)}}</h3>
                        <p>{{__('Pages')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-object-group"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{number_format($group_count)}}</h3>
                        <p>{{__('Groups')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        @if(!(env('FACEBOOK_APP_ID') && env('FACEBOOK_APP_SECRET')))
            <div class="alert alert-warning">
                <strong>{{__('Warning!')}}</strong> {{__('You need to add the Facebook API details before you can connect with Facebook. Visit the Settings Section to do so now!')}}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title" >
                            <span> {{__('Facebook Accounts')}}</span>
                        </div>
                        <a href="{{route('facebook.login')}}" class="btn btn-circle btn-success pull-right btn-sm">
                            <i class="fa fa-plus"></i> {{__('Add')}}
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @foreach($fb_accounts as $fb_account)
                                <div class="col-md-4">
                                    <!-- Widget: user widget style 1 -->
                                    <div class="box box-widget widget-user">
                                        <!-- Add the bg color to the header using any of the bg-* classes -->
                                        <div class="widget-user-header bg-aqua-active">
                                            <h3 class="widget-user-username">{{$fb_account->profile_name}}</h3>
                                            <h5 class="widget-user-desc">
                                                {{ucwords($fb_account->status)}}
                                                <a href="{{LaravelLocalization::getLocalizedURL(null, '/facebook/'.$fb_account->id.'/refresh', [])}}"
                                                   class="label label-success ig-refresh">
                                                    {{__('Refresh')}}
                                                </a>
                                                <a class="label label-danger"
                                                   href="{{LaravelLocalization::getLocalizedURL(null, '/facebook/account/'.$fb_account->id.'/delete', [])}}"
                                                   onclick="event.preventDefault();document.getElementById('delete-account-{{$fb_account->id}}').submit();">
                                                    {{__('Delete')}}
                                                </a>
                                                <form id="delete-account-{{$fb_account->id}}" action="{{LaravelLocalization::getLocalizedURL(null, '/facebook/account/'.$fb_account->id.'/delete', [])}}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </h5>
                                        </div>
                                        <div class="widget-user-image">
                                            <img class="img-circle" src="{{(!$fb_account->profile_picture)? url('img/user.jpg'): url($fb_account->profile_picture)}}" alt="User Avatar">
                                        </div>
                                        <div class="box-footer">
                                            <div class="row">
                                                <div class="col-sm-6 border-right">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($fb_account->pages->count())}}</h5>
                                                        <span class="description-text">{{__('PAGES')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($fb_account->groups->count())}}</h5>
                                                        <span class="description-text">{{__('GROUPS')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(isset($fb_accounts) && !count($fb_accounts))
                                <h3 class="text-center">{{__('No facebook account added yet!')}}</h3>
                            @endif
                        </div>
                        {{$fb_accounts->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
   <script>
        jQuery(document).ready(function(){
            $(".bs_confirmation").on("confirmed.bs.confirmation", function(e) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                document.getElementById('delete-account-'+id).submit();
            })
        })
    </script>
@endpush