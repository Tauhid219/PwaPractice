<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles Management') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-12">
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
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Roles</h3>
                    <div class="card-tools">
                        @can('create roles')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Role
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td><span class="badge badge-info">{{ $role->name }}</span></td>
                                <td>
                                    @php
                                        $displayLimit = 3;
                                        $allPermissions = $role->permissions->pluck('name')->implode(', ');
                                    @endphp
                                    
                                    @foreach($role->permissions->take($displayLimit) as $permission)
                                        <span class="badge badge-secondary small">{{ $permission->name }}</span>
                                    @endforeach
                                    
                                    @if($role->permissions->count() > $displayLimit)
                                        <span class="badge badge-info small cursor-pointer" title="{{ $allPermissions }}">
                                            +{{ $role->permissions->count() - $displayLimit }} more
                                        </span>
                                    @endif
                                    
                                    @if($role->name === 'super-admin')
                                        <span class="badge badge-success">All Permissions</span>
                                    @endif
                                </td>
                                <td>
                                    @can('edit roles')
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning btn-xs">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    @if(!in_array($role->name, ['super-admin', 'student']))
                                    @can('delete roles')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</x-admin-layout>
