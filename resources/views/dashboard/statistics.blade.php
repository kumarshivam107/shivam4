@extends('layouts.master')
@section('page_title', __('Dashboard'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{__('Statistics')}} <small>{{__('charts, recent events and reports')}}</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('General Statitics')}}</h4>
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
            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('Posts Chart')}}</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="areaChart" style="height:250px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-6">
                <!-- Chat box -->
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">{{__('Log')}}</h3>
                    </div>
                    <div class="box-body chat" id="log-box">
                        <ul class="products-list product-list-in-box">
                            @foreach($logs as $log)
                                <li class="item">
                                    <?php
                                    $icon = 'bell-o';
                                    switch($log->type){
                                        case 'fb':
                                            $icon = 'facebook';
                                            break;
                                        case 'tw':
                                            $icon = 'twitter';
                                            break;
                                        case 'ig':
                                            $icon = 'instagram';
                                            break;
                                    }

                                    $date = new \Carbon\Carbon($log->created_at);
                                    ?>
                                    <div class="product-img label-{{$log->state}} text-center" style="width:55px; padding: 5px">
                                        <i class="fa fa-3x fa-{{$icon}}"></i>
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">
                                            <span class="pull-right">{{$date->diffForHumans()}}</span>
                                        </a>
                                        <span>
                                        {{$log->message}}
                                    </span>
                                    </div>
                                </li>
                            @endforeach
                            @if(!count($logs))
                                <li class="item">
                                    <h3 class="text-center">{{__('No Log found yet!')}}</h3>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <!-- /.chat -->
                    <div class="box-footer">
                        {{$logs->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="/bower_components/chart.js/Chart.js" type="text/javascript"></script>
    <script>
        $(function(){
            $('#log-box').slimScroll({
                height: '250px'
            });
        });
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
                        label               : 'Instagram',
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
                                    foreach($ig_post_stat as $stat){
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
                        label               : 'Facebook',
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
                                foreach($fb_post_stat as $stat){
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
                        label               : 'Twitter',
                        fillColor           : 'rgba(22,225,200,0.8)',
                        strokeColor         : 'rgba(22,225,200,0.8)',
                        pointColor          : 'rgba(22,225,200,1)',
                        pointStrokeColor    : 'rgba(22,225,200,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(22,225,200,1)',
                        data                : [
                            <?php
                            for($i = 1; $i<=12; $i++){
                                $val = 0;
                                foreach($tw_post_stat as $stat){
                                    if($stat->month == $i){
                                        $val = $stat->count;
                                        break;
                                    }
                                }
                                echo $val.',';
                            }
                            ?>
                        ]
                    }
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