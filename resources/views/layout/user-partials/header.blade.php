<style type="text/css">
    .Notification-list--small
    {
        min-height: 80px;
    }
    .Notification__status--read
    {
        background-color: #ff6c60;
    }
</style>
<header id="header" class="ui-header">

    <div class="navbar-header">
        <!--logo start-->
        <a href="#" class="navbar-brand">
            <span class="logo"><img src="{{ asset('imgs/new_logo.png') }}" alt=""/></span>
            <span class="logo-compact"><img src="{{ asset('imgs/jata.png') }}" alt=""/></span>
        </a>
        <!--logo end-->
    </div>

    <div class="navbar-collapse nav-responsive-disabled">

        <!--toggle buttons start-->
        <ul class="nav navbar-nav">
            <li>
                <a class="toggle-btn" data-toggle="ui-nav" href="#">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- toggle buttons end -->

        <!--notification start-->
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-usermenu">
                <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <div class="user-avatar"><img src="{{ asset('img/avatar.png') }}" alt="..."></div>
                    <span class="hidden-sm hidden-xs">{{ Auth::user()->name }}</span>
                    <!--<i class="fa fa-angle-down"></i>-->
                    <span class="caret hidden-sm hidden-xs"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                    <li><a href="{{ route('user_profile') }}"><i class="fa fa-user"></i>  Profile</a></li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
        <!--notification end-->

    </div>

</header>