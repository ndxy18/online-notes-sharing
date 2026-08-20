document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var links = document.getElementById('navLinks');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
        });
    }

    // auto-hide flash alerts after 4s
    document.querySelectorAll('.alert').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });

    // confirm before delete
    document.querySelectorAll('.confirm-delete').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm('Pakka delete karna hai? Yeh wapas nahi hoga.')) {
                e.preventDefault();
            }
        });
    });

    // PWA install prompt (Android/Chrome) — shows an "Install App" button
    var deferredPrompt;
    var installBtn = document.getElementById('installBtn');
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        if (installBtn) installBtn.style.display = 'inline-flex';
    });
    if (installBtn) {
        installBtn.addEventListener('click', function () {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function () {
                deferredPrompt = null;
                installBtn.style.display = 'none';
            });
        });
    }
    window.addEventListener('appinstalled', function () {
        if (installBtn) installBtn.style.display = 'none';
    });
});
