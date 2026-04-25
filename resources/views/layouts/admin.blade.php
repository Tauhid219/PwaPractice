<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }} | Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  
  @stack('styles')
  
  <script>
    // Initial theme check to prevent flash
    (function() {
      const theme = localStorage.getItem('admin-theme') || 'light';
      if (theme === 'dark') {
        document.documentElement.classList.add('dark-mode');
        // We'll apply body class after DOM is ready or via inline if needed
      }
    })();
  </script>
</head>
<body class="hold-transition sidebar-mini">
<script>
  // Add dark mode to body immediately after it's defined to minimize flicker
  if (localStorage.getItem('admin-theme') === 'dark') {
    document.body.classList.add('dark-mode');
  }
</script>
<div class="wrapper">

  <!-- Navbar -->
  @include('layouts.admin.navbar')
  <script>
    // Sync navbar theme immediately after it's rendered to prevent flash
    (function() {
      const theme = localStorage.getItem('admin-theme') || 'light';
      if (theme === 'dark') {
        const nav = document.getElementById('main-nav');
        if (nav) {
          nav.classList.remove('navbar-white', 'navbar-light');
          nav.classList.add('navbar-dark');
        }
        const icon = document.getElementById('theme-icon');
        if (icon) {
          icon.classList.remove('fa-moon');
          icon.classList.add('fa-sun');
        }
      }
    })();
  </script>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.admin.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @if (isset($header))
              {{ $header }}
            @endif
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        {{ $slot }}
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('layouts.admin.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
  $(function() {
    const themeToggle = $('#theme-toggle');
    const themeIcon = $('#theme-icon');
    const mainNav = $('#main-nav');
    const body = $('body');

    // Toggle click
    themeToggle.on('click', function(e) {
      e.preventDefault();
      const currentTheme = localStorage.getItem('admin-theme') || 'light';
      const newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
      
      localStorage.setItem('admin-theme', newTheme);
      window.location.reload();
    });

    // Theme icon and nav classes are now handled by inline scripts to prevent flash,
    // but we'll keep the toggle logic here.
  });
</script>

@stack('scripts')
@include('partials._flash_messages')
</body>
</html>
