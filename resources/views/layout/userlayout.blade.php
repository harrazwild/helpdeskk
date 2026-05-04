<!DOCTYPE html>
<html>
<head>
    @include('layout.user-partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">   
</head>
<body>
    <div id="ui" class="ui">

    <!--header start-->
    @include('layout.user-partials.header')
    <!--header end-->

    <!--sidebar start-->
    @include('layout.user-partials.nav')
    <!--sidebar end-->

    <!--main content start-->
    <div id="content" class="ui-content ui-content-aside-overlay">
            
        @yield('content')

    </div>
    <!--main content end-->

    <!--footer start-->
    @include('layout.user-partials.footer')
    <!--footer end-->

    </div>
    @include('layout.user-partials.scripts')
</body>

</html>
