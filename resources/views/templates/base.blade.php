<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Quản lý sinh viên</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" href="https://laravel.com/img/favicon/favicon.ico">
      <!-- Bootstrap core CSS -->
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> 
      <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}">
      <!-- PAGE LEVEL PLUGINS STYLES -->	
      <link href="{{ asset('assets/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/plugins/morris/morris.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-datepicker/datepicker.css') }}">
      <link href="{{ asset('assets/css/plugins/select2/select2.css') }}" rel="stylesheet"> 
      <!-- REQUIRE FOR SPEECH COMMANDS -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/gritter/jquery.gritter.css') }}" />
      <!-- Tc core CSS -->
      <link id="qstyle" rel="stylesheet" href="{{ asset('assets/css/themes/style.css') }}">
      <!-- Add custom CSS here -->
      <link rel="stylesheet" href="{{ asset('assets/css/only-for-demos.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}"> 
      <!-- End custom CSS here -->
      <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
      <script src="assets/js/respond.min.js"></script>
      <![endif]-->
      <!--[if lte IE 8]>
      <script src="assets/js/plugins/easypiechart/easypiechart.ie-fix.js"></script>
      <![endif]-->
   </head>
   <body>
      <div id="wrapper">
         <div id="main-container">
            @include('templates.header')
            @include('templates.sidebar')
            <!-- BEGIN MAIN PAGE CONTENT -->
            <div id="page-wrapper">
                @yield('content')
            </div>
            <!-- /#page-wrapper -->	  
            <!-- END MAIN PAGE CONTENT -->
         </div>
      </div>
      @include('templates.footer')
      <!-- core JavaScript -->
      <script src="{{ asset('assets/js/jquery.min.js') }}"></script> 
      <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script> 
      <script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script> 
      <script src="{{ asset('assets/js/plugins/pace/pace.min.js') }}"></script>
      <!-- PAGE LEVEL PLUGINS JS -->	
      <script src="{{ asset('assets/js/plugins/daterangepicker/moment.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/daterangepicker/daterangepicker.js') }}"></script>	
      <script src="{{ asset('assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script> 
	   <script src="{{ asset('assets/js/plugins/datatables/datatables.js') }}"></script> 
      <script src="{{ asset('assets/js/plugins/datatables/datatables.responsive.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/select2/select2.min.js') }}"></script> 
      <!-- Themes Core Scripts -->	
      <script src="{{ asset('assets/js/main.js') }}"></script>
      <!-- REQUIRE FOR SPEECH COMMANDS -->
      <script src="{{ asset('assets/js/speech-commands.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/gritter/jquery.gritter.min.js') }}"></script>		
      @yield('scripts')
   </body>
</html>