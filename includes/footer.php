<footer>
    <div class="container">
        <p>📚 NotesHub — Online Notes Sharing System &copy; <?= date('Y') ?> | Built with PHP &amp; MySQL for students, by a student.</p>
    </div>
</footer>
<script src="<?= isset($base) ? $base : '' ?>assets/js/script.js"></script>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('<?= isset($base) ? $base : '' ?>service-worker.js')
            .catch(function (err) { console.log('SW registration failed:', err); });
    });
}
</script>
</body>
</html>
