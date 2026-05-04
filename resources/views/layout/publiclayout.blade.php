<!DOCTYPE html>
<html>

  <head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>Sistem Helpdesk</title>  

    <meta name="keywords" content="helpdesk JAN" />
    <meta name="description" content="Sistem Helpdesk - Jabatan Audit Negara">
    <meta name="author" content="syarizan">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <!-- Web Fonts  -->   
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light%7CPlayfair+Display:400&amp;display=swap" rel="stylesheet" type="text/css">

    <!-- Tabs and Accordions Dependencies -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-vertical-tabs/bootstrap.vertical-tabs.min.css') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">   
    <link rel="stylesheet" href="{{ asset('vendor/animate/animate.compat.css') }}">   
    <link rel="stylesheet" href="{{ asset('vendor/simple-line-icons/css/simple-line-icons.min.css') }}">      
    <link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.min.css') }}">
    <link href="{{ asset('bower_components/toastr/toastr.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-shop.css') }}">

    <!-- Current Page CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/rs-plugin/css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/rs-plugin/css/layers.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/rs-plugin/css/navigation.css') }}">
    
    <!-- Demo CSS -->
    <style>
    a 
    {
      text-decoration:none !important;
    }
    a:hover, a:focus
    {
      color: #ffc107 !important;
    }
    a
    {
      color: #fff !important;
    }
    .row-custom
    {
    min-height: 150px;
    }
    .help-block
    {
      color: red;
      font-size: 9pt;
    }
    i[class^='icon-'], i[class*=' icon-']
    {
    font-size: 16px;
    }
    col-form-label
    {
      text-align: left;
    }
    .btn-custom{
      background-color: #ffc107;
      color: #fff;
    }
    .btn-custom:hover, .btn-custom:focus, .btn-custom:active
    {
      background-color: #FFCC00 !important;
      color: #fff !important;
    }
    </style>

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{ asset('css/skins/default.css') }}">   

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Head Libs -->
    <script src="{{ asset('vendor/modernizr/modernizr.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> 
    
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @if(config('recaptchav3.sitekey'))
      {!! RecaptchaV3::initJs() !!}
    @endif   

  </head>
  <body style="background-color: #0088CC !important">

    <section class="section section-background section-height-5 overlay overlay-show overlay-op-8 border-0 m-0" style="background-image: url({{ asset('img/it-technical.jpg') }}); background-size: cover; background-position: center;">
      <div class="container">
        <div class="col text-center appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
          <h1 class="font-weight-bold text-color-light mb-2">SISTEM HELPDESK</h1>
          <h2 class="text-color-light mb-2">JABATAN AUDIT NEGARA</h2>
        </div>
      </div>
    </section>

    <section class="section bg-primary section-height-2 border-0 m-0">
      @yield('content')
    </section>

    <section class="section bg-primary border-0 m-0">
      <div class="container">
        <div class="row justify-content-center text-center text-lg-left py-4">
          <div class="col-lg-auto appear-animation" data-appear-animation="fadeInRightShorter">
            <div class="feature-box feature-box-style-2 d-block d-lg-flex mb-4 mb-lg-0">
              <div class="feature-box-icon">
                <i class="icon-location-pin icons text-color-light"></i>
              </div>
              <div class="feature-box-info pl-1">
                <h5 class="font-weight-light text-color-light opacity-7 mb-0">
                  Aras 2, Blok F3, Kompleks F,<br>
                  Pusat Pentadbiran Kerajaan Persekutuan<br>
                  62000 Putrajaya.
                </h5>
                <p class="text-color-light font-weight-semibold mb-0">
                  ISNIN - JUMAAT<br>
                  8:00 pagi - 5:00 petang
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
            <div class="feature-box feature-box-style-2 d-block d-lg-flex mb-4 mb-lg-0 px-xl-4 mx-lg-5">
              <div class="feature-box-icon">
                <i class="icon-call-out icons text-color-light"></i>
              </div>
              <div class="feature-box-info pl-1">
                <h5 class="font-weight-light text-color-light opacity-7 mb-0">HUBUNGI KAMI</h5>
                <a href="tel:+60380911000" class="text-color-light font-weight-semibold text-decoration-none">+603 8091 1911</a>
              </div>
            </div>
          </div>
          <div class="col-lg-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="400">
            <div class="feature-box feature-box-style-2 d-block d-lg-flex">
              <div class="feature-box-icon">
                <i class="icon-envelope icons text-color-light"></i>
              </div>
              <div class="feature-box-info pl-1">
                <h5 class="font-weight-light text-color-light opacity-7 mb-0">EMEL</h5>
                  <p class="text-color-light font-weight-semibold mb-0">
                      helpdesk@audit.gov.my
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Vendor -->   
    <script src="{{ asset('vendor/jquery.appear/jquery.appear.min.js') }}"></script>    
    <script src="{{ asset('vendor/jquery.easing/jquery.easing.min.js') }}"></script>    
    <script src="{{ asset('vendor/jquery.cookie/jquery.cookie.min.js') }}"></script>        
    <script src="{{ asset('vendor/popper/umd/popper.min.js') }}"></script>    
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>   
    <script src="{{ asset('vendor/common/common.min.js') }}"></script>    
    <script src="{{ asset('vendor/jquery.validation/jquery.validate.min.js') }}"></script>        
    <script src="{{ asset('vendor/jquery.lazyload/jquery.lazyload.min.js') }}"></script>    
    <script src="{{ asset('vendor/isotope/jquery.isotope.min.js') }}"></script>       
    <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>   
    <script src="{{ asset('vendor/vide/jquery.vide.min.js') }}"></script>   
    <script src="{{ asset('vendor/vivus/vivus.min.js') }}"></script>
    <script src="{{ asset('bower_components/toastr/toastr.js') }}"></script>
    <script src="{{ asset('bower_components/sweetalert/dist/sweetalert.js') }}"></script>

    <!--(remove-empty-lines-end)-->

    <!-- Theme Base, Components and Settings -->
    <script src="{{ asset('js/theme.js') }}"></script>

    <!-- Current Page Vendor and Views -->
    <script src="{{ asset('vendor/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>   
    <script src="{{ asset('vendor/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ asset('js/views/view.contact.js') }}"></script>

    <!-- Theme Initialization Files -->
    <script src="{{ asset('js/theme.init.js') }}"></script>

    <!-- Examples -->
    <script src="{{ asset('js/examples/examples.portfolio.js') }}"></script>

    <script type="text/javascript">
      $(document).ready(function(){

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

  </body>

</html>
