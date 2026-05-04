<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}" />
<title>Sistem Helpdesk - Jabatan Audit Negara</title>

<!-- inject:css -->
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/simple-line-icons/css/simple-line-icons.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/weather-icons/css/weather-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}">
<!-- endinject -->

<!-- Main Style  -->
<link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">

<!--Data Table-->
<link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
<link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
<link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.css') }}" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<!-- <link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet"> -->


<!-- Rickshaw Chart Depencencies -->
<link rel="stylesheet" href="{{ asset('bower_components/rickshaw/rickshaw.min.css') }}">

<!--easypiechart
<link rel="stylesheet" href="{{ asset('assets/js/jquery-easy-pie-chart/easypiechart.css') }}">-->

<!-- Bootstrap Date Range Picker Dependencies -->
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">

<!-- Bootstrap DatePicker Dependencies -->
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">

<!-- Bootstrap TimePicker Dependencies -->
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">

<!--horizontal-timeline-->
<link rel="stylesheet" href="{{ asset('assets/js/horizontal-timeline/css/style.css') }}">

<!--sweetalert-->
<link href="{{ asset('bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet">

<!--toastr-->
<link href="{{ asset('bower_components/toastr/toastr.css') }}" rel="stylesheet">

<!--summer note-->
<link rel="stylesheet" href="{{ asset('bower_components/summernote/dist/summernote.css') }}">

<!-- Select2 Dependencies -->
<link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.css') }}">

<script src="{{ asset('assets/js/modernizr-custom.js') }}"></script>
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Google Captcha -->
<script async src="https://www.google.com/recaptcha/api.js"></script>

<style type="text/css">
	.custom-bg
	{
		background-color: #898989;
	}
</style>