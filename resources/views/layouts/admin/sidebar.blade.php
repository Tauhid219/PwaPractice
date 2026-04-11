<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('home') }}" class="brand-link">
    <img src="{{ asset('favicon.png') }}" alt="Genius Kids Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Genius Kids</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ Auth::user()->avatar }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        <small class="text-info text-uppercase font-weight-bold" style="font-size: 10px;">
          {{ Auth::user()->getRoleNames()->implode(', ') ?: 'User' }}
        </small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @hasanyrole('super-admin|admin|editor|moderator')
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        @endhasanyrole

        @can('manage categories')
        <li class="nav-item">
          <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list"></i>
            <p>Categories</p>
          </a>
        </li>
        @endcan

        @can('manage questions')
        <li class="nav-item">
          <a href="{{ route('admin.questions.index') }}" class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-question-circle"></i>
            <p>Questions</p>
          </a>
        </li>
        @endcan

        @can('manage exams')
        <li class="nav-item">
          <a href="{{ route('admin.live-exams.index') }}" class="nav-link {{ request()->routeIs('admin.live-exams.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-trophy"></i>
            <p>Live Exams</p>
          </a>
        </li>
        @endcan

        @canany(['manage users', 'manage roles'])
        <li class="nav-header">USER MANAGEMENT</li>
        
        @can('manage users')
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Manage Users</p>
          </a>
        </li>
        @endcan

        @can('manage roles')
        <li class="nav-item">
          <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Manage Roles</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-key"></i>
            <p>Manage Permissions</p>
          </a>
        </li>
        @endcan
        @endcanany

        <li class="nav-header">SYSTEM</li>
        <li class="nav-item">
          <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Profile Settings</p>
          </a>
        </li>

        @role('guest')
        <li class="nav-item">
          <a href="{{ route('profile.progress') }}" class="nav-link {{ request()->routeIs('profile.progress') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>My Progress</p>
          </a>
        </li>
        @endrole

        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" class="nav-link text-danger" 
               onclick="event.preventDefault(); this.closest('form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </form>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
