<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}: {{ $role->name }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Update Role Details</h3>
                </div>
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Role Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter role name" value="{{ old('name', $role->name) }}" required {{ $role->name === 'super-admin' ? 'readonly' : '' }}>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="d-block mb-3">Update Permissions</label>
                            @if($role->name === 'super-admin')
                                <p class="text-success"><i class="fas fa-check-circle"></i> Super Admin has all permissions by default.</p>
                            @endif

                            @php
                                $groupedPermissions = $permissions->groupBy(function($permission) {
                                    $parts = explode(' ', $permission->name);
                                    return count($parts) > 1 ? end($parts) : 'system';
                                });
                            @endphp

                            <div class="row {{ $role->name === 'super-admin' ? 'text-muted' : '' }}">
                                @foreach($groupedPermissions as $group => $perms)
                                <div class="col-md-4 mb-4">
                                    <div class="card card-outline card-warning h-100 shadow-sm">
                                        <div class="card-header py-2 bg-light">
                                            <h3 class="card-title text-uppercase font-weight-bold" style="font-size: 0.8rem;">
                                                <i class="fas fa-folder-open mr-1 text-warning"></i> {{ $group === 'system' ? 'General' : $group }}
                                            </h3>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($perms as $permission)
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input class="custom-control-input" type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->name }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                    {{ $role->name === 'super-admin' ? 'disabled' : '' }}>
                                                <label for="perm_{{ $permission->id }}" class="custom-control-label font-weight-normal text-sm">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning" {{ $role->name === 'super-admin' ? 'disabled' : '' }}>Update Role</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
