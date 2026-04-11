<!-- Navbar -->
<nav id="main-nav" class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('home') }}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Theme Selector -->
    <li class="nav-item">
      <a class="nav-link" href="#" id="theme-toggle" role="button" title="Toggle Dark/Light Mode">
        <i class="fas fa-moon" id="theme-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User Dropdown Menu (Logout) -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" title="Roles: {{ Auth::user()->getRoleNames()->implode(', ') ?: 'User' }}">
        <i class="fas fa-user"></i> {{ Auth::user()->name }}
      </a>
      <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
        <a href="{{ route('profile.edit') }}" class="dropdown-item">Profile</a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <a href="{{ route('logout') }}" class="dropdown-item" 
             onclick="event.preventDefault(); this.closest('form').submit();">
            Logout
          </a>
        </form>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
