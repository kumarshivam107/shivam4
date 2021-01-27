<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('page_title') | {{__('SocioScheduler')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css">
    <link href="/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    @stack('css')
    <link href="/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" >
    <link href="/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" >
    <link href="/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" >
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
    @include('layouts.header')
    @include('layouts.side')
    <div class="content-wrapper">
        @yield('content')
    </div>
    @include('layouts.footer')
</div>
<!--[if lt IE 9]>
<script src="/plugins/respond.min.js"></script>
<script src="/plugins/excanvas.min.js"></script>
<script src="/plugins/ie8.fix.min.js"></script>
<![endif]-->
<script src="/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/plugins/jquery.blockui.min.js" type="text/javascript"></script>
@stack('js')
<script src="/plugins/app.js" type="text/javascript"></script>
<script src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script src="/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
<script src="/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="/bower_components/raphael/raphael.min.js" type="text/javascript"></script>
<script src="/bower_components/morris.js/morris.min.js" type="text/javascript"></script>
<script src="/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/bower_components/fastclick/lib/fastclick.js" type="text/javascript"></script>
<script src="/dist/js/adminlte.min.js" type="text/javascript"></script>
<script src="/js/custom.js" type="text/javascript"></script>
<script>
    $('.make-switch').bootstrapSwitch();
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script>
    function toastError(msg){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.error(msg, "Error")
    }

    function toastInfo(msg) {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "20000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.info(msg, "Information")
    }

    function toastSuccess(msg){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.success(msg, "Success")
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@if(count($errors->all()))
    @foreach ($errors->all() as $error)
        <script>
            App.alert({
                container: '',
                place: 'append',
                type: 'danger',
                message: '{{ $error }}',
                close: true,
                reset: false,
                focus: true,
                closeInSeconds: 0,
                icon: 'warning'
            });

            toastError("{{$error}}");
        </script>
    @endforeach
@endif
@if(Session::has('success'))
    <script>
        App.alert({
            container: '',
            place: 'append',
            type: 'success',
            message: '{{ Session::get("success") }}',
            close: true,
            reset: true,
            focus: true,
            closeInSeconds: 0,
            icon: 'check'
        });
        toastSuccess("{{ Session::get('success') }}")
    </script>
@endif
@if(Session::has('status'))
    <script>
        toastInfo("{{Session::get('status')}}")
    </script>
@endif

@if(Session::has('error'))
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "20000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.error("{{ Session::get('error') }}", "Error")
    </script>
@endif
</body>
</html>
