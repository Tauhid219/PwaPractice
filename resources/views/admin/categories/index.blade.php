<x-admin-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Categories</h1>
                </div>
                <div class="col-sm-6 d-flex justify-content-end align-items-center">
                    @can('create categories')
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add New Category
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">


            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Order</th>
                                    <th>Name</th>
                                    <th>Icon</th>
                                    <th class="text-right" style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{ $category->order }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            <i class="{{ $category->icon }} mr-2"></i> {{ $category->icon }}
                                        </td>
                                        <td class="text-right">
                                            @can('edit categories')
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @endcan
                                            @can('delete categories')
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline-block confirm-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer clearfix">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
            <!-- /.card -->
        </div>
    </div>
</x-admin-layout>
