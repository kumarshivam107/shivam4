@extends('layouts.master')
@section('page_title', __('Instagram'))

@push('css')
    <link href="/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Datatable -->
    <link href="/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <section class="content-header">
        <h1>
            {{__('Follow Back')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Set up Follow Back Schedule!')}}</h4>
            <p>{{__('Note: There is a delay between every scheduled activities. This is necessary in order to comply with Instagram\'s terms and conditions.')}}</p>
            <p class="alert alert-warning">
                <strong>{{__('Important!')}}</strong> {{__('Only 5 profiles are followed per 5 minutes, and also only one schedule per account can be active at a time.')}}
            </p>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title">
                            <span>{{__('New Schedule!')}}</span>
                        </div>
                    </div>
                    <div class="box-body form">
                        <form role="form" method="post">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="account">{{__('Select a Instagram Account')}}</label>
                                            <select id="account" class="form-control select2" name="instagram_account">
                                                <option></option>
                                                @foreach($ig_accounts as $ig_account)
                                                    <option value="{{$ig_account->id}}">{{$ig_account->profile_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="select2-button-addons-single-input-group-lg" class="control-label">{{__('Add Exceptions')}}</label>
                                            <div class="input-group select2-bootstrap-append">
                                                <select id="select2-button-addons-single-input-group-lg" class="form-control js-data-ajax" name="exception[]" multiple>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-single-input-group-lg">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form">
                                        <div class="form-horizontal form-bordered">
                                            <div class="form-group">
                                                <label class="col-md-8">{{__('Exclude verified users?')}}</label>
                                                <div class="col-md-4">
                                                    <input type="checkbox" {{(old('account'))? ((old('exclude_verified') == 'yes')? 'checked': ''): ''}} value="yes" class="make-switch" name="exclude_verified"  data-size="mini">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-8">{{__('Exclude non-verified users?')}}</label>
                                                <div class="col-md-4">
                                                    <input type="checkbox" {{(old('account'))? ((old('exclude_non_verified') == 'yes')? 'checked': ''): ''}} value="yes" class="make-switch" name="exclude_non_verified" data-size="mini">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('Specify the date range of schedule')}}</label>
                                            <div class="input-group input-large input-daterange" data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                <input type="text" class="form-control date-picker" name="date_range">
                                            </div>
                                            <span class="help-block"> {{__('Select date range')}} </span>
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
        <div class="callout callout-info">
            <h4>{{__('Schedule List')}}</h4>
            @if($alerts = Session::get('alerts'))
                @foreach($alerts as $alert)
                    <p class="alert alert-{{$alert['type']}} alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{$alert['msg']}}
                    </p>
                @endforeach
            @endif
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="box box-info">
                    <div class="box-body">
                        <table class="table table-striped table-hover table-bordered" id="table_data">
                            <thead>
                            <tr>
                                <th>{{__('Instagram Account')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>
                                <th>{{__('Ex. Verified')}}</th>
                                <th>{{__('Ex. Non-verified')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
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
    <script src="/plugins/moment.min.js" type="text/javascript"></script>

    <script src="/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

    <!-- Datatables -->
    <script src="/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script>
        var ComponentsDateTimePickers = function() {
            jQuery().daterangepicker && $(".date-picker").daterangepicker({
                rtl: !1,
                orientation: "left",
                autoclose: !0,
                locale: {
                    format: 'YYYY/MM/DD'
                }
            })
        }

        var AutoCompleteInit = function() {
            $('.js-data-ajax').select2({
                width: "off",
                placeholder: "{{__('Choose Users...')}}",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('search-ig-non-following')}}',
                    dataType: 'json',
                    type: 'POST',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            id: function (){
                                var value = $('#account').val();
                                return (value.length)? value: 0
                            }
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $("button[data-select2-open]").click(function() {
                $("#" + $(this).data("select2-open")).select2("open");
            });
        }

        var initTable = function () {
            var table = $('#table_data');

            var oTable = table.DataTable({

                processing: true,
                serverSide: true,
                "ajax": {
                    "async": true,
                    "type": "POST",
                    "url": '{{route('ig-follow-back-data')}}',
                    "data": {}
                },
                columns: [
                    {data: 'instagram_account'},
                    {data: 'start_date'},
                    {data: 'end_date'},
                    {data: 'exclude_verified'},
                    {data: 'exclude_non_verified'},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],

                "language": {
                    "aria": {
                        "sortAscending": ": {{__('activate to sort column ascending')}}",
                        "sortDescending": ": {{__('activate to sort column descending')}}"
                    },
                    "emptyTable": "{{__('No data available in table')}}",
                    "info": "{{__('Showing').' _START_ '.__('to').' _END_ '.__('of').' _TOTAL_ '.__('entries')}}",
                    "infoEmpty": "{{__('No entries found')}}",
                    "infoFiltered": "{{'(filtered1 '.__('from').' _MAX_ '.__('total entries').')'}}",
                    "lengthMenu": "{{'_MENU_ '.__('entries')}}",
                    "search": "{{__('Search:')}}",
                    "zeroRecords": "{{__('No matching records found')}}"
                },

                // setup responsive extension: http://datatables.net/extensions/responsive/
                responsive: true,

                //"ordering": false, disable column ordering
                //"paging": false, disable pagination

                "order": [
                    [0, 'asc']
                ],

                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 20,

                //"dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            });
        }
        jQuery(document).ready(function() {
            ComponentsDateTimePickers();
            $.fn.select2.defaults.set("theme", "bootstrap");
            var s = "{{__('Select an Account')}}";
            $(".select2, .select2-multiple").select2({
                placeholder: s,
                width: null
            })

            // Initialize Select2 Autocomplete...
            AutoCompleteInit();

            // Initialize Datatable...
            initTable();
        });
    </script>
@endpush