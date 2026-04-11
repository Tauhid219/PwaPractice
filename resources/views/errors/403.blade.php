<x-admin-layout>
    <div class="error-page">
        <h2 class="headline text-danger">403</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Access Prohibited.</h3>

            <p>
                You do not have the necessary permissions to access this page.
                Meanwhile, you may <a href="{{ route('admin.dashboard') }}">return to dashboard</a>.
            </p>
        </div>
    </div>
</x-admin-layout>
