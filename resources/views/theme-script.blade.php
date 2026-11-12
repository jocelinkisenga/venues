{{-- Appliqué avant le premier rendu : pas de flash clair/sombre --}}
<script>
    (function () {
        var mode = localStorage.getItem('theme');
        var dark = mode === 'dark'
            || (mode !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    })();
</script>