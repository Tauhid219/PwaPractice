<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="row g-3">
    @csrf
    @method('patch')

    <div class="col-12">
        <label for="name" class="form-label text-dark fw-bold">নাম</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
    </div>

    <div class="col-12">
        <label for="email" class="form-label text-dark fw-bold">ইমেইল ঠিকানা</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 text-warning">
                <small>আপনার ইমেইল ঠিকানা ভেরিফাই করা হয়নি।
                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">ভেরিফিকেশন ইমেইল পুনরায় পাঠাতে এখানে ক্লিক করুন।</button>
                </small>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-success mt-1">
                        <small>একটি নতুন ভেরিফিকেশন লিংক আপনার ইমেইলে পাঠানো হয়েছে।</small>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="col-12 mt-4 d-flex align-items-center">
        <button type="submit" class="btn btn-primary py-2 px-4 rounded-pill">সংরক্ষণ করুন</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success ms-3"><i class="fa fa-check"></i> <b>সফলভাবে সংরক্ষিত</b></span>
        @endif
    </div>
</form>
