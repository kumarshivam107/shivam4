@extends('layouts.master')
@section('page_title', __('Facebook'))

@push('css')
    <!-- Datatable -->
    <link href="/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <section class="content-header">
        <h1>
            {{__('Manage Pages & Groups')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Pages List')}}</h4>
            <p>{{__('The following is a table list of all the pages that belongs to each account, and which he/she administers.')}}</p>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="box box-success">
                    <div class="box-body">
                        <table class="table table-striped table-hover table-bordered" id="table_data_1">
                            <thead>
                            <tr>
                                <th>{{__('Facebook Account')}}</th>
                                <th>{{__('Page Name')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="callout callout-info">
            <h4>{{__('Groups List')}}</h4>
            <p>{{__('The following is a table list of all the group to which each account belongs to, and he/she administers.')}}</p>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="box box-success">
                    <div class="box-body">
                        <table class="table table-striped table-hover table-bordered" id="table_data_2">
                            <thead>
                            <tr>
                                <th>{{__('Facebook Account')}}</th>
                                <th>{{__('Group Name')}}</th>
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

    <!-- Datatables -->
    <script src="/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script>
        var initTable1 = function () {
            var table1 = $('#table_data_1');

            var oTable1 = table1.DataTable({

                processing: true,
                serverSide: true,
                "ajax": {
                    "async": true,
                    "type": "POST",
                    "url": '{{route('fb-page-data')}}',
                    "data": {}
                },
                columns: [
                    {data: 'facebook_account'},
                    {data: 'page_name'},
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

        var initTable2 = function () {
            var table2 = $('#table_data_2');

            var oTable2 = table2.DataTable({

                processing: true,
                serverSide: true,
                "ajax": {
                    "async": true,
                    "type": "POST",
                    "url": '{{route('fb-group-data')}}',
                    "data": {}
                },
                columns: [
                    {data: 'facebook_account'},
                    {data: 'group_name'},
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
            initTable1();
            initTable2();
        });
    </script>

@endpush