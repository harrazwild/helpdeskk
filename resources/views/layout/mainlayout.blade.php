<!DOCTYPE html>
<html>
<head>
    @include('layout.partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">   
</head>
<body>
    <div id="ui" class="ui">

    <!--header start-->
    @include('layout.partials.header')
    <!--header end-->

    <!--sidebar start-->
    @include('layout.partials.nav')
    <!--sidebar end-->

    <!--main content start-->
    <div id="content" class="ui-content ui-content-aside-overlay">
            
        @yield('content')

    </div>
    <!--main content end-->

    <!--footer start-->
    @include('layout.partials.footer')
    <!--footer end-->

    </div>
    @include('layout.partials.scripts')
</body>

</html>
