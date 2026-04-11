<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User Account') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Update User Information</h3>
                </div>
                <!-- form start -->
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New Password (leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="New Password">
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm New Password">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label>Assign Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->name }}" 
                                                {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                                                {{ (auth()->id() === $user->id && !auth()->user()->hasRole('super-admin')) ? 'disabled' : '' }}>
                                            <label for="role_{{ $role->id }}" class="custom-control-label">{{ $role->name }}</label>
                                            @if(auth()->id() === $user->id && !auth()->user()->hasRole('super-admin'))
                                                <input type="hidden" name="roles[]" value="{{ $role->name }}">
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(auth()->id() === $user->id && !auth()->user()->hasRole('super-admin'))
                                <small class="text-danger mt-2 d-block">* You cannot change your own roles unless you are a Super Admin.</small>
                            @endif
                            @error('roles')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-info">Update User Account</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancel</a>
                        </div>
                        @if(auth()->id() === $user->id && auth()->user()->hasRole('guest'))
                        <div>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary"><i class="fas fa-chart-line"></i> View My Progress</a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
