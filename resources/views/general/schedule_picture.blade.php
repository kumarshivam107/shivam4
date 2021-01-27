@extends('layouts.master')
@section('page_title', __('General'))

@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h1 class="page-title"> {{__('Schedule Picture')}} </h1>
        <!-- END PAGE HEADER-->

    </div>
@endsection
@push('js')
    <script src="/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/raphael-min.js" type="text/javascript"></script>
@endpush