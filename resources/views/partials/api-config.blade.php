<script>
    window.ECL_API_BASE_URL = @json(rtrim(config('services.ecl.api_base_url') ?: url('/api'), '/') . '/');
    window.eclApiUrl = function (path) {
        const endpoint = String(path).replace(/^\/?api\/?/, '');
        return new URL(endpoint, window.ECL_API_BASE_URL).toString();
    };
</script>
