@push('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('scripts')
<script defer async src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function confirmation(text = null, config = {}) {
        if (typeof Swal === 'undefined') return confirm('Are you sure?');

        return new Promise(async (resolve, reject) => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: text || "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: "#d61f47",
                reverseButtons: true,
                ...config
            });
            if (result.isConfirmed) {
                resolve(true);
            } else {
                reject(false);
            }
        })
    }
</script>
@endpush