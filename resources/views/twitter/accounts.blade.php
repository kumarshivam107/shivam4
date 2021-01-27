@extends('layouts.master')
@section('page_title', __('Twitter'))

@section('content')
    <section class="content-header">
        <h1>
            {{__('My Accounts')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Twitter Statistics')}}</h4>
        </div>
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
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
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{number_format($dm_count)}}</h3>
                        <p>{{__('Direct Message')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{number_format($follow_back_count)}}</h3>
                        <p>{{__('Follow Back')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{number_format($unfollow_count)}}</h3>
                        <p>{{__('Unfollow')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('Follow / Unfollow Chart')}}</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="areaChart" style="height:250px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        @if(!(env('TWITTER_ACCESS_TOKEN') && env('TWITTER_ACCESS_TOKEN_SECRET') && env('TWITTER_CONSUMER_KEY') && env('TWITTER_CONSUMER_SECRET')))
            <div class="alert alert-warning">
                <strong>{{__('Warning!')}}</strong> {{__('You need to add the Twitter API details before you can connect with Twitter. Visit the Settings Section to do so now!')}}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title">
                            <span> {{__('Twitter Accounts')}}</span>
                        </div>
                        <a href="{{route('twitter.login')}}" class="btn btn-circle btn-success pull-right btn-sm">
                            <i class="fa fa-plus"></i> {{__('Add')}}
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @foreach($tw_accounts as $tw_account)
                                <div class="col-md-4">
                                    <!-- Widget: user widget style 1 -->
                                    <div class="box box-widget widget-user">
                                        <!-- Add the bg color to the header using any of the bg-* classes -->
                                        <div class="widget-user-header bg-aqua-active">
                                            <h3 class="widget-user-username">{{$tw_account->profile_name}}</h3>
                                            <h5 class="widget-user-desc">
                                                {{ucwords($tw_account->status)}}
                                                <a href="{{LaravelLocalization::getLocalizedURL(null, '/twitter/'.$tw_account->id.'/refresh', [])}}"
                                                   class="label label-success ig-refresh">
                                                    {{__('Refresh')}}
                                                </a>
                                                <a class="label label-danger"
                                                   href="{{LaravelLocalization::getLocalizedURL(null, '/twitter/account/'.$tw_account->id.'/delete', [])}}"
                                                   onclick="event.preventDefault();document.getElementById('delete-account-{{$tw_account->id}}').submit();">
                                                    {{__('Delete')}}
                                                </a>
                                                <form id="delete-account-{{$tw_account->id}}" action="{{LaravelLocalization::getLocalizedURL(null, '/twitter/account/'.$tw_account->id.'/delete', [])}}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </h5>
                                        </div>
                                        <div class="widget-user-image">
                                            <img class="img-circle" src="{{(!$tw_account->profile_picture)? url('img/user.jpg'): url($tw_account->profile_picture)}}" alt="User Avatar">
                                        </div>
                                        <div class="box-footer">
                                            <div class="row">
                                                <div class="col-sm-6 border-right">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($tw_account->following)}}</h5>
                                                        <span class="description-text">{{__('FOLLOWING')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($tw_account->followers)}}</h5>
                                                        <span class="description-text">{{__('FOLLOWERS')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(isset($tw_accounts) && !count($tw_accounts))
                                <h3 class="text-center">{{__('No twitter account added yet!')}}</h3>
                            @endif
                        </div>
                        {{$tw_accounts->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="/bower_components/chart.js/Chart.js" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function(){
            $(".bs_confirmation").on("confirmed.bs.confirmation", function(e) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                document.getElementById('delete-account-'+id).submit();
            })

        })
    </script>
    <script>
        $(function () {
            // Get context with jQuery - using jQuery's .get() method.
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
            // This will get the first returned node in the jQuery collection.
            var areaChart       = new Chart(areaChartCanvas)

            var areaChartData = {
                labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label               : 'Follow Back',
                        fillColor           : 'rgba(209,10,79,0.8)',
                        strokeColor         : 'rgba(209,10,79,0.8)',
                        pointColor          : 'rgba(209,10,79,1)',
                        pointStrokeColor    : 'rgba(209,10,79,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(209,10,79,1)',
                        data                : [
                            <?php
                            for($i = 1; $i<=12; $i++){
                                $val = 0;
                                foreach($follow_back_stat as $stat){
                                    if($stat->month == $i){
                                        $val = $stat->count;
                                        break;
                                    }
                                }
                                echo $val.',';
                            }
                            ?>
                        ]
                    },
                    {
                        label               : 'Unfollow',
                        fillColor           : 'rgba(7,63,243,0.8)',
                        strokeColor         : 'rgba(7,63,243,0.8)',
                        pointColor          : 'rgba(7,63,243,1)',
                        pointStrokeColor    : 'rgba(7,63,243,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(7,63,243,1)',
                        data                : [
                            <?php
                            for($i = 1; $i<=12; $i++){
                                $val = 0;
                                foreach($unfollow_stat as $stat){
                                    if($stat->month == $i){
                                        $val = $stat->count;
                                        break;
                                    }
                                }
                                echo $val.',';
                            }
                            ?>
                        ]
                    },
                ]
            }

            var areaChartOptions = {
                showScale: true,
                scaleShowGridLines: false,
                scaleGridLineColor: 'rgba(0,0,0,.05)',
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: true,
                bezierCurve: true,
                bezierCurveTension: 0.3,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                maintainAspectRatio: true,
                responsive: true,
                multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
            }
            areaChart.Line(areaChartData, areaChartOptions)
        });
    </script>
@endpush