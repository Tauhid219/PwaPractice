<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Manage Users</h1>
            </div>
        </div>
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
                                                    <img src="{{ asset('vendor/adminlte/dist/img/avatar5.png') }}" class="img-circle elevation-1" alt="User Image" style="width: 30px;">
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
                                            @if($user->is_admin)
                                                <span class="badge bg-success">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">Student</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info mr-2">
                                                <i class="fas fa-eye mr-1"></i> View Progress
                                            </a>
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to change this user\'s role?');">
                                                    @csrf
                                                    @method('PUT')
                                                    @if($user->is_admin)
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-user-minus mr-1"></i> Remove Admin
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-user-plus mr-1"></i> Make Admin
                                                        </button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-muted small italic">No actions</span>
                                            @endif
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
