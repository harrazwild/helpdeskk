<!-- inject:js -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('bower_components/autosize/dist/autosize.min.js') }}"></script>
<!-- endinject -->

<!--Data Table-->
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables-tabletools/js/dataTables.tableTools.js') }}"></script>
<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables-colvis/js/dataTables.colVis.js') }}"></script>
<script src="{{ asset('bower_components/datatables-responsive/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('bower_components/datatables-scroller/js/dataTables.scroller.js') }}"></script>

<script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script> -->


<!--highcharts-->
<script src="{{ asset('bower_components/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('bower_components/highcharts/highcharts-more.js') }}"></script>
<script src="{{ asset('bower_components/highcharts/modules/exporting.js') }}"></script>
<!-- <script src="assets/js/init-highcharts-inner.js"></script> -->

<!--sweetalert -->
<script src="{{ asset('bower_components/sweetalert/dist/sweetalert.js') }}"></script>

<!--toastr-->
<script src="{{ asset('bower_components/toastr/toastr.js') }}"></script>

<!--summer note-->
<script src="{{ asset('bower_components/summernote/dist/summernote.js') }}"></script>

<!--sparkline-->
<script src="{{ asset('bower_components/bower-jquery-sparkline/dist/jquery.sparkline.retina.js') }}"></script>
<script src="{{ asset('assets/js/init-sparkline.js') }}"></script>

<!--echarts-->
<script type="text/javascript" src="{{ asset('assets/js/echarts/echarts-all-3.js') }}"></script>

<!-- Select2 Dependencies -->
<script src="{{ asset('bower_components/select2/dist/js/select2.js') }}"></script>

<!-- Bootstrap Date Range Picker Dependencies -->
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!--basic line echarts init-->
<script type="text/javascript">
    $(document).ready(function(){
        
        $('.select2').select2();
                
        @php
        if(Session::has('alert')){
        @endphp
        toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-left",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "3000",
                        "hideDuration": "0",
                        "timeOut": "0",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "allowHtml": true 
                        }

        toastr['{{ Session::get('alert')['type'] }}']('{{ Session::get('alert')['message'] }}', '{{ Session::get('alert')['title'] }}');
        @php    
        }
        @endphp
    });

    function inputLimiter(e,allow) {
        var AllowableCharacters = '';

        if (allow == 'Letters'){AllowableCharacters=' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';}
        if (allow == 'Numbers'){AllowableCharacters='1234567890';}
        if (allow == 'Floats'){AllowableCharacters='1234567890.';}
        if (allow == 'NumbersLetters'){AllowableCharacters=' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-.\'"';}
        if (allow == 'Default'){AllowableCharacters=' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@-_.,`/()\'"';}
        if (allow == 'NameCharacters'){AllowableCharacters=' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@-.\'';}
        if (allow == 'NameCharactersAndNumbers'){AllowableCharacters=' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@/\'';}

        var k = document.all?parseInt(e.keyCode): parseInt(e.which);
      
        if (k!=13 && k!=8 && k!=0){
          if ((e.ctrlKey==false) && (e.altKey==false)) {
            return (AllowableCharacters.indexOf(String.fromCharCode(k))!=-1);
          } else {
            return true;
          }
        } else {
          return true;
        }
    }

</script>

<!--horizontal-timeline-->
<script src="{{ asset('assets/js/horizontal-timeline/js/jquery.mobile.custom.min.js') }}"></script>
<script src="{{ asset('assets/js/horizontal-timeline/js/main.js') }}"></script>

<!-- Common Script   -->
<script src="{{ asset('dist/js/main.js') }}"></script>