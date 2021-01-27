<header class="main-header">
    <a href="/" class="logo">
        <span class="logo-mini"><b>S-</b>S</span>
        <span class="logo-lg"><b>Socio</b>Scheduler</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/img/user.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="/img/user.jpg" class="img-circle" alt="User Image">
                            <p>
                                {{__('Hello,')}} {{Auth::user()->name}}
                                @php
                                    $created_at = new Carbon\Carbon(Auth::user()->created_at);
                                @endphp
                                <small>Since {{$created_at->diffForHumans()}}</small>
                            </p>
                        </li>
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('ig-accounts')}}">{{__('Instagram')}}</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('tw-accounts')}}">{{__('Twitter')}}</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('fb-accounts')}}">{{__('Facebook')}}</a>
                                </div>
                            </div>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('set-profile')}}" class="btn btn-default btn-flat">{{__('Profile')}}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('lock-screen') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('lock-screen-form').submit();"
                                   class="btn btn-default btn-flat">
                                    {{__('Lock Screen')}}
                                </a>
                                <form id="lock-screen-form" action="{{ route('lock-screen') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"
                                   class="btn btn-default btn-flat">
                                    {{__('Logout')}}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>