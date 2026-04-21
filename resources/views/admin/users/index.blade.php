<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Manage Users</h1>
            </div>
            @can('create users')
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add New User
                </a>
            </div>
            @endcan
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <img src="{{ $user->avatar }}" class="img-circle elevation-1" alt="User Image" style="width: 30px;">
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if(auth()->id() === $user->id)
                                                        <span class="badge badge-info ml-1">You</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @forelse($user->roles as $role)
                                                <span class="badge badge-primary">{{ $role->name }}</span>
                                            @empty
                                                <span class="badge badge-secondary">No Role</span>
                                            @endforelse
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                @can('manage users')
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="View Progress">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('edit users')
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit Roles">
                                                    <i class="fas fa-user-tag"></i>
                                                </a>
                                                @endcan
                                                @can('delete users')
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline confirm-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
