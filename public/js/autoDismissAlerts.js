(function () {
    function dismissAlert(alert) {
        window.setTimeout(function () {
            alert.style.transition = 'opacity 0.25s ease';
            alert.style.opacity = '0';
            window.setTimeout(function () {
                alert.remove();
            }, 250);
        }, 3000);
    }

    function initAutoDismissAlerts() {
        document.querySelectorAll('[data-auto-dismiss="success"], .alert.alert-success').forEach(dismissAlert);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAutoDismissAlerts);
    } else {
        initAutoDismissAlerts();
    }
})();
