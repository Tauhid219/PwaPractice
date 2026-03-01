<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
    <a href="{{ url('/') }}" class="navbar-brand">
        <h1 class="m-0 text-primary"><i class="fa fa-book-reader me-3"></i>জিনিয়াস কিডস</h1>
    </a>
    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto">
            <a href="{{ url('/') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ url('/about') }}" class="nav-item nav-link {{ Request::is('about') ? 'active' : '' }}">About Us</a>
            <a href="{{ url('/classes') }}" class="nav-item nav-link {{ Request::is('classes') ? 'active' : '' }}">Classes</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ Request::is('facility') || Request::is('team') || Request::is('call-to-action') || Request::is('appointment') || Request::is('testimonial') || Request::is('404') ? 'active' : '' }}" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                    <a href="{{ url('/facility') }}" class="dropdown-item">School Facilities</a>
                    <a href="{{ url('/team') }}" class="dropdown-item">Popular Teachers</a>
                    <a href="{{ url('/call-to-action') }}" class="dropdown-item">Become A Teachers</a>
                    <a href="{{ url('/appointment') }}" class="dropdown-item">Make Appointment</a>
                    <a href="{{ url('/testimonial') }}" class="dropdown-item">Testimonial</a>
                    <a href="{{ url('/404') }}" class="dropdown-item">404 Error</a>
                </div>
            </div>
            <a href="{{ url('/contact') }}" class="nav-item nav-link {{ Request::is('contact') ? 'active' : '' }}">Contact Us</a>
        </div>
        <a href="javascript:void(0)" id="install-btn" class="btn btn-primary rounded-pill px-3 d-none">অ্যাপ ইনস্টল করুন <i class="fa fa-download ms-2"></i></a>
    </div>
</nav>
