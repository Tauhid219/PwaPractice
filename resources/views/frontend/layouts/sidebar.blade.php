<aside class="col-lg-3 d-none d-lg-block sticky-top" style="top: 80px; height: calc(100vh - 80px); overflow-y: auto;">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-primary text-white pb-1 pt-3">
            <h5 class="mb-0 text-white"><i class="fa fa-list me-2"></i> বিষয়সমূহ</h5>
        </div>
        <div class="list-group list-group-flush">
            @foreach($globalCategories as $sidebarCategory)
                <a href="{{ route('category.chapters', $sidebarCategory->slug) }}" 
                   class="list-group-item list-group-item-action d-flex align-items-center py-3 {{ Request::is('category/' . $sidebarCategory->slug . '*') ? 'active bg-light text-primary fw-bold' : '' }}">
                    <div class="facility-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="fa {{ $sidebarCategory->icon }}"></i>
                    </div>
                    <span>{{ $sidebarCategory->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</aside>
