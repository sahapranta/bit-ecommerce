<script defer src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
<script>
    function initEditor(element = document.querySelector('#content'), config = {}) {
        ClassicEditor
            .create(element, {
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                simpleUpload: {
                    uploadUrl: "{{ route('admin.pages.upload') }}",
                    withCredentials: true,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                },
                ...config
            })
            .catch(error => console.error(error));
    }
</script>