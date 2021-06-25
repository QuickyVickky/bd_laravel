
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
  <title>NobleUI Laravel Admin Dashboard Template</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="p8aOAR1eVo2kqZZu5bv4ZxpCMFphMej0u4UcIdH0">
  
  <link rel="shortcut icon" href="{{ asset('admin_assets/favicon.ico')}} ">

  <!-- plugin css -->
  <link href="{{ asset('admin_assets/assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('admin_assets/assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('admin_assets/assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <!-- end plugin css -->
    <link href="{{ asset('admin_assets/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />

  <!-- common css -->
  <link href="{{ asset('admin_assets/css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @yield('css')

  </head>

<body data-base-url="#">

  <script src="{{ asset('admin_assets/assets/js/spinner.js') }} "></script>

  <div class="main-wrapper" id="app">

  @include('admin.layout.sidebar')
<div class="page-wrapper">
    @include('admin.layout.header')

    
    @yield('main')

    @include('admin.layout.footer')


  </div>
</div>


    <!-- base js -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('admin_assets/js/app.js') }} "></script>
  <script src="{{ asset('admin_assets/assets/plugins/feather-icons/feather.min.js') }} "></script>
  <script src="{{ asset('admin_assets/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }} "></script>
    <!-- end base js -->
    <!-- plugin js -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/chartjs/Chart.min.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/jquery.flot/jquery.flot.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/jquery.flot/jquery.flot.resize.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/apexcharts/apexcharts.min.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/plugins/progressbar-js/progressbar.min.js') }} "></script> -->
    <!-- end plugin js -->
    <!-- common js -->
  <script src="{{ asset('admin_assets/assets/js/template.js') }} "></script>
 

    <!-- end common js -->
  <!-- <script src="{{ asset('admin_assets/assets/js/dashboard.js') }} "></script> -->
  <!-- <script src="{{ asset('admin_assets/assets/js/datepicker.js') }} "></script> -->

  @yield('script')


</body>
</html>
