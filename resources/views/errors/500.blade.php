<!DOCTYPE html>
<html>
    
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}" />
		<title>Sistem Helpdesk - Jabatan Audit Negara</title>

        <!-- inject:css -->
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/simple-line-icons/css/simple-line-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}">
        <!-- endinject -->

        <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">

        <script src="{{ asset('assets/js/modernizr-custom.js') }}"></script>
    </head>
    <body>
        <div class="sign-in-wrapper">
            <div class="sign-container lock-bg">
                <div class="text-center">
                    <h1 class="error-txt">500</h1>
                    <h3>Internal Server Error </h3>
                    <p>Looks like Something went wrong. We apologize.
                        You can go back to main page</p>
                    <br/>
                    <a href="{{ route('dashboard') }}" class="btn btn-info">Utama</a>
                </div>
            </div>
        </div>

        <!-- inject:js -->
        <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
        <script src="{{ asset('bower_components/autosize/dist/autosize.min.js') }}"></script>
        <!-- endinject -->

        <script src="{{ asset('dist/js/main.js') }}"></script>

    </body>

</html>
