<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{__('Register')}} | {{__('SocioScheduler')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<body class=" login">
<div class="logo">
    <a href="/">
        <img src="/img/logo.png" alt="{{__('SocioScheduler')}}" />
    </a>
</div>
<div class="content">
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}
        <h3 class="font-green">{{__('Sign Up')}}</h3>
        @if(session('status'))
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                <span>{{ session('status') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li> {{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <p class="hint"> {{__('Enter your personal details below:')}} </p>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('Full Name')}}</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="{{__('Full Name')}}" name="name" value="{{ old('name') }}" />
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('Email')}}</label>
            <input class="form-control placeholder-no-fix" type="email" placeholder="{{__('Email')}}" name="email" value="{{ old('email') }}" />
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('Password')}}</label>
            <input class="form-control placeholder-no-fix" type="password" placeholder="{{__('Password')}}" name="password" required />
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('Confirm Password')}}</label>
            <input class="form-control placeholder-no-fix" type="password" placeholder="{{__('Confirm Password')}}" name="password_confirmation" required />
        </div>
        <div class="form-actions">
            <a href="{{route('login')}}" id="register-back-btn" class="btn green btn-outline">{{__('Back')}}</a>
            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">{{__('Submit')}}</button>
        </div>
    </form>
</div>
<div class="copyright"> 2014 © Metronic. Admin Dashboard Template. </div>
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
<script src="/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="/js/app.js" type="text/javascript"></script>
</body>
</html>
