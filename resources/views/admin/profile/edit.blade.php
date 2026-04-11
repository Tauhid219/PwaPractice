<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile Settings</h1>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-6">
            <!-- Profile Information -->
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit mr-1"></i> Update Profile Information
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Change -->
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-key mr-1"></i> Update Password
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Account Info / Avatar -->
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $user->avatar }}"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center font-weight-bold mt-2">{{ $user->name }}</h3>

                    <p class="text-info text-center text-uppercase font-weight-bold" style="font-size: 0.9rem;">
                        {{ $user->getRoleNames()->first() ?? 'Administrator' }}
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email:</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Member Since:</b> <a class="float-right">{{ $user->created_at->format('M Y') }}</a>
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-block rounded-pill shadow-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> <b>Logout</b>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
