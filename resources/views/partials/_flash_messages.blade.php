@if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif

    @if(session('warning'))
        Toast.fire({
            icon: 'warning',
            title: "{{ session('warning') }}"
        });
    @endif

    @if(session('info'))
        Toast.fire({
            icon: 'info',
            title: "{{ session('info') }}"
        });
    @endif

    @if($errors->any())
        Toast.fire({
            icon: 'error',
            title: "তথ্য সঠিক নয়!",
            text: "{{ $errors->first() }}"
        });
    @endif

    // Global Confirmation Handler
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.classList.contains('confirm-delete') || form.getAttribute('data-confirm')) {
            if (form.dataset.swalDone) return;
            
            e.preventDefault();
            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: form.getAttribute('data-confirm-text') || "এটি ডিলিট করলে আর ফিরে পাওয়া যাবে না!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, ডিলিট করুন!',
                cancelButtonText: 'না, থাক'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.swalDone = 'true';
                    form.submit();
                }
            });
        }
    });
</script>
@endif
