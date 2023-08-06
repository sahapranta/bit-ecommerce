<script>
    document.querySelectorAll('.copy-me').forEach(function(item) {
        item.addEventListener('click', function() {
            navigator.clipboard.writeText(item.dataset.info);
            let title = item.dataset.bsOriginalTitle;
            item.dataset.bsOriginalTitle = "{{ __('Copied') }}";
            var tooltip = bootstrap.Tooltip.getInstance(item);
            tooltip.show();
            item.addEventListener('mouseleave', () => {
                tooltip.hide();
                item.dataset.bsOriginalTitle = title;
            });
        });
    });
</script>