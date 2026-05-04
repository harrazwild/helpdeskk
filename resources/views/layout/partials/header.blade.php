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
            <span class="logo">&nbsp;&nbsp;&nbsp;&nbsp;<img src="{{ asset('imgs/new_logo.png') }}" alt=""/></span>
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
            @if(Auth::user()->role_id != 1 && Auth::user()->role_id != 5)
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell-o"></i>
                    <span class="badge">
                    @if(Auth::user()->role_id == 2)
                    {!! App\Helper\Utilities::totalOnTask() + App\Helper\Utilities::totalNew() !!}
                    @else
                    {!! App\Helper\Utilities::totalOnTask() !!}
                    @endif
                    </span>
                </a>

                <!--dropdown -->
                <ul class="dropdown-menu dropdown-menu--responsive">
                    @if(Auth::user()->role_id == 3)
                    <div class="dropdown-header">Senarai Mesyuarat</div>
                    <ul class="Notification-list Notification-list--small niceScroll list-group">
                        @foreach(App\Helper\Utilities::Mesyuarat() as $data)
                        <li class="Notification list-group-item">
                            <button class="Notification__status Notification__status--read" type="button" name="button"></button>
                            <a href="{{ route('show_meeting', Crypt::encrypt($data->id)) }}">
                                <div class="Notification__avatar Notification__avatar--danger pull-left" href="#">
                                    <i class="Notification__avatar-icon fa fa-bookmark-o"></i>
                                </div>
                                <div class="Notification__highlight">
                                    <p class="Notification__highlight-excerpt"><b>#{{ $data->application_no }}</b></p>
                                    <p class="Notification__highlight-time">{{ $data->location }}</p>
                                    <p class="Notification__highlight-time">{!! App\Helper\Utilities::meetingTime($data->id) !!}</p>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <div class="dropdown-header">Dalam Tindakan</div>
                    <ul class="Notification-list Notification-list--small niceScroll list-group">
                        @foreach(App\Helper\Utilities::Task() as $data)
                        <li class="Notification list-group-item">
                            <button class="Notification__status Notification__status--read" type="button" name="button"></button>
                            <a href="{{ route('show_technical', Crypt::encrypt($data->id)) }}">
                                <div class="Notification__avatar Notification__avatar--danger pull-left" href="#">
                                    <i class="Notification__avatar-icon fa fa-bookmark-o"></i>
                                </div>
                                <div class="Notification__highlight">
                                    <p class="Notification__highlight-excerpt"><b>#{{ $data->application_no }}</b></p>
                                    <p class="Notification__highlight-time">{{ $data->remarks }}</p>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @if(Auth::user()->role_id == 2)
                    <div class="dropdown-header">Aduan Baru</div>
                    <ul class="Notification-list Notification-list--small niceScroll list-group">
                        @foreach(App\Helper\Utilities::New() as $row)
                        <li class="Notification list-group-item">
                            <button class="Notification__status Notification__status--read" type="button" name="button"></button>
                            <a href="{{ route('show_coordinator', Crypt::encrypt($row->id)) }}">
                                <div class="Notification__avatar Notification__avatar--danger pull-left" href="#">
                                    <i class="Notification__avatar-icon fa fa-bookmark-o"></i>
                                </div>
                                <div class="Notification__highlight">
                                    <p class="Notification__highlight-excerpt"><b>#{{ $row->application_no }}</b></p>
                                    <p class="Notification__highlight-time">{{ $row->remarks }}</p>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </ul>
                <!--/ dropdown -->

            </li>
            @endif

            <li class="dropdown dropdown-usermenu">
                <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <div class="user-avatar"><img src="{{ asset('img/avatar.png') }}" alt="..."></div>
                    <span class="hidden-sm hidden-xs">{{ Auth::user()->name }}</span>
                    <!--<i class="fa fa-angle-down"></i>-->
                    <span class="caret hidden-sm hidden-xs"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                    <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i>  Profile</a></li>
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