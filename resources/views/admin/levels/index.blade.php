<x-admin-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Levels</h1>
                </div>
                <div class="col-sm-6 d-flex justify-content-end align-items-center">
                    @can('create levels')
                    <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add New Level
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('admin.levels.index') }}" class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="category_id" class="mr-2 d-inline-block">Filter by Category:</label>
                                <select name="category_id" id="category_id" class="form-control d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request('category_id'))
                                <a href="{{ route('admin.levels.index') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times mr-1"></i> Clear Filter
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Order</th>
                                    <th>Category</th>
                                    <th>Level Name</th>
                                    <th>Required Score</th>
                                    <th>Questions</th>
                                    <th>Status</th>
                                    <th class="text-right" style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($levels as $level)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{ $level->order }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $level->category->name ?? 'Global' }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $level->name }}</strong>
                                        </td>
                                        <td>{{ $level->required_score_to_unlock }}</td>
                                        <td>
                                            <span class="badge badge-light border">{{ $level->questions_count }}</span>
                                        </td>
                                        <td>
                                            @if($level->is_free)
                                                <span class="badge badge-success">Free</span>
                                            @else
                                                <span class="badge badge-danger">Paid / Locked</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @can('edit levels')
                                            <a href="{{ route('admin.levels.edit', $level->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @endcan
                                            @can('delete levels')
                                            <form action="{{ route('admin.levels.destroy', $level->id) }}" method="POST" class="d-inline-block confirm-delete" onsubmit="return confirm('Are you sure you want to delete this level?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-4">No levels found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $levels->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
