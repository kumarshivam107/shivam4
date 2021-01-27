<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{__('Lock Screen')}} | {{__('SocioScheduler')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<body class="">
<div class="page-lock">
    <div class="page-logo">
        <a class="brand" href="index.html">
            <img src="/img/logo.png" alt="{{__('SocioScheduler')}}"/>
        </a>
    </div>
    <div class="page-body">
        <div class="lock-head"><i class="fa fa-key"></i> {{__('Locked')}} </div>
        <div class="lock-body">
            <div class="lock-cont">
                <div class="lock-item">
                    <div class="pull-left lock-avatar-block">
                        <img alt="" style="max-height: 100px" src="/img/user.jpg" />
                    </div>
                </div>
                <div class="lock-item lock-item-full">
                    <form class="lock-form pull-left" action="{{route('unlock-screen')}}" method="post">
                        {{csrf_field()}}
                        <h4>{{Auth::user()->name}}</h4>
                        @if (Session::has('error'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <ul>
                                    <li> {{Session::get('error')}}</li>
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{__('Password')}}" name="password" />
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn red uppercase">{{__('Login')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lock-bottom">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                {{'Not '.Auth::user()->name.'?'}}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <div class="page-footer-custom">{{\Carbon\Carbon::now()->year}} &copy; {{__('SocioScheduler')}}. All Rights Reserved</div>
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
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/js/app.js" type="text/javascript"></script>
<script>
    $(document).ready(function()
    {
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });
    })
</script>
</body>
</html>