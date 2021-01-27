@extends('layouts.master')
@section('page_title', __('Instagram'))

@push('css')

@endpush
@section('content')
    <section class="content-header">
        <h1>
            {{__('My Accounts')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Instagram Statistics')}}</h4>
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
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title" >
                            <span> {{__('Instagram Accounts')}}</span>
                        </div>
                        <a href="#login" data-toggle="modal" class="btn btn-circle btn-success pull-right">
                            <i class="fa fa-plus"></i> {{__('Add')}}
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @foreach($ig_accounts as $ig_account)
                                <div class="col-md-4">
                                    <!-- Widget: user widget style 1 -->
                                    <div class="box box-widget widget-user">
                                        <!-- Add the bg color to the header using any of the bg-* classes -->
                                        <div class="widget-user-header bg-aqua-active">
                                            <h3 class="widget-user-username">{{$ig_account->profile_name}}</h3>
                                            <h5 class="widget-user-desc">
                                                {{ucwords($ig_account->status)}}
                                                <a href="javascript:;"
                                                   onclick="$('#instagram_id').val({{$ig_account->id}});$('#refresh').modal('show');"
                                                   class="label label-success ig-refresh">
                                                    {{__('Refresh')}}
                                                </a>
                                                <a class="label label-danger"
                                                   href="{{LaravelLocalization::getLocalizedURL(null, '/instagram/account/'.$ig_account->id.'/delete', [])}}"
                                                   onclick="event.preventDefault();document.getElementById('delete-account-{{$ig_account->id}}').submit();">
                                                    {{__('Delete')}}
                                                </a>
                                                <form id="delete-account-{{$ig_account->id}}" action="{{LaravelLocalization::getLocalizedURL(null, '/instagram/account/'.$ig_account->id.'/delete', [])}}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </h5>
                                        </div>
                                        <div class="widget-user-image">
                                            <img class="img-circle" src="{{(!$ig_account->profile_picture)? url('img/user.jpg'): url($ig_account->profile_picture)}}" alt="User Avatar">
                                        </div>
                                        <div class="box-footer">
                                            <div class="row">
                                                <div class="col-sm-6 border-right">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($ig_account->following)}}</h5>
                                                        <span class="description-text">{{__('FOLLOWING')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="description-block">
                                                        <h5 class="description-header">{{number_format($ig_account->followers)}}</h5>
                                                        <span class="description-text">{{__('FOLLOWERS')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(isset($ig_accounts) && !count($ig_accounts))
                                <h3 class="text-center">{{__('No instagram account added yet!')}}</h3>
                            @endif
                        </div>
                        {{$ig_accounts->links()}}
                    </div>
                </div>
            </div>
        </div>

        <div id="login" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form role="form" method="post">
                        {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{__('Instagram Login')}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>{{__('Note:')}}</b> {{__('If you get a "checkpoint required message", visit your instagram account on your mobile phone to confirm access to the account!')}}</p>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" name="ig_username" value="{{old('ig_username')}}">
                                            <label for="form_control_1">{{__('Username')}}</label>
                                         </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <input type="password" class="form-control" name="ig_password">
                                            <label for="form_control_1">{{__('Password')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn ">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Login')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Accouunt Refresh -->
        <div id="refresh" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form role="form" action="{{route('ig-refresh')}}" method="post">
                        {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{__('Refresh Instagram Account')}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>{{__('Note:')}}</b> {{__('If you get a "checkpoint required message", visit your instagram account on your mobile phone to confirm access to the account!')}}</p>
                                </div>
                                <input id="instagram_id" type="hidden" name="instagram_id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" name="ig_username" value="{{old('ig_username')}}">
                                            <label for="form_control_1">{{__('Username')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <input type="password" class="form-control" name="ig_password">
                                            <label for="form_control_1">{{__('Password')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Login')}}</button>
                        </div>
                    </form>
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