<aside class="main-sidebar">
    <section class="sidebar" id="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/img/user.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{__('MAIN NAVIGATION')}}</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{__('Dashboard')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('statistics')}}"><i class="fa fa-circle-o"></i> {{__('Statistics')}}</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-globe"></i>
                    <span>{{__('General')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('schedule-post')}}"><i class="fa fa-calendar-check-o"></i>{{__('Schedule Posts')}}</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-instagram"></i>
                    <span>{{__('Instagram')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('ig-accounts')}}"><i class="fa fa-users"></i>{{__('My Accounts')}}</a></li>
                    <li><a href="{{route('ig-follow-back')}}"><i class="fa fa-user-plus"></i>{{__('Auto Follow Back')}}</a></li>
                    <li><a href="{{route('ig-dm')}}"><i class="fa fa-envelope"></i>{{__('Auto DM')}}</a></li>
                    <li><a href="{{route('ig-unfollow')}}"><i class="fa fa-ban"></i>{{__('Auto Unfollow')}}</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-facebook"></i>
                    <span>{{__('Facebook')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('fb-accounts')}}"><i class="fa fa-users"></i>{{__('My Accounts')}}</a></li>
                    <li><a href="{{route('fb-page-group')}}"><i class="fa fa-object-group"></i>{{__('Manage Pages & Groups')}}</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-twitter"></i>
                    <span>{{__('Twitter')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('tw-accounts')}}"><i class="fa fa-users"></i>{{__('My Accounts')}}</a></li>
                    <li><a href="{{route('tw-follow-back')}}"><i class="fa fa-user-plus"></i>{{__('Auto Follow Back')}}</a></li>
                    <li><a href="{{route('tw-dm')}}"><i class="fa fa-envelope"></i>{{__('Auto DM')}}</a></li>
                    <li><a href="{{route('tw-unfollow')}}"><i class="fa fa-ban"></i>{{__('Auto Unfollow')}}</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>{{__('Settings')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('set-fb-api')}}"><i class="fa fa-facebook"></i>{{__('Facebook API')}}</a></li>
                    <li><a href="{{route('set-tw-api')}}"><i class="fa fa-twitter"></i>{{__('Twitter API')}}</a></li>
                    <li><a href="{{route('set-profile')}}"><i class="fa fa-user"></i>{{__('Profile Settings')}}</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>