document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Auto-dismiss alerts after 4s
    const dismissAlerts = () => {
        document.querySelectorAll('.alert').forEach(a => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
            bsAlert?.close();
        });
    };

    setTimeout(dismissAlerts, 4000);
});
