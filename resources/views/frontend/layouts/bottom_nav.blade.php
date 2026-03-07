<nav class="navbar fixed-bottom navbar-light bg-white shadow-lg d-lg-none d-flex justify-content-around py-2 border-top">
    <a href="{{ url('/') }}" class="text-center text-decoration-none {{ Request::is('/') ? 'text-primary' : 'text-muted' }}">
        <i class="fa fa-home fs-4"></i>
        <span class="d-block" style="font-size: 12px;">হোম</span>
    </a>
    
    <a href="#" class="text-center text-decoration-none text-muted" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoriesMenu">
        <i class="fa fa-th-large fs-4"></i>
        <span class="d-block" style="font-size: 12px;">বিষয়</span>
    </a>

    @auth
        <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('profile.edit') }}" class="text-center text-decoration-none text-muted">
            <i class="fa fa-user fs-4"></i>
            <span class="d-block" style="font-size: 12px;">প্রোফাইল</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="text-center text-decoration-none text-muted">
            <i class="fa fa-sign-in-alt fs-4"></i>
            <span class="d-block" style="font-size: 12px;">লগিন</span>
        </a>
    @endauth
</nav>

<!-- Mobile Categories Offcanvas -->
<div class="offcanvas offcanvas-bottom rounded-top" tabindex="-1" id="mobileCategoriesMenu" aria-labelledby="mobileCategoriesMenuLabel" style="height: 60vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="mobileCategoriesMenuLabel">বিষয় নির্বাচন করুন</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3">
            @foreach($globalCategories as $mobileCategory)
            <div class="col-6">
                <a href="{{ route('category.levels', $mobileCategory->slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center py-3 {{ Request::is('category/' . $mobileCategory->slug . '*') ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                        <i class="fa {{ $mobileCategory->icon }} fs-3 mb-2 {{ Request::is('category/' . $mobileCategory->slug . '*') ? 'text-white' : 'text-primary' }}"></i>
                        <h6 class="mb-0 {{ Request::is('category/' . $mobileCategory->slug . '*') ? 'text-white' : '' }}">{{ $mobileCategory->name }}</h6>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
