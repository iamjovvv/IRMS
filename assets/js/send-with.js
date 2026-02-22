
document.addEventListener('DOMContentLoaded', () => {

    const authSelect = document.querySelector('select[name="auth_method"]');

    const institutionalFields = document.querySelectorAll('.form__field--institutional');

    const phoneFields = document.querySelectorAll('.form__field--phone');

    const googleFields = document.querySelectorAll('.form__field--google');

    function resetFields() {
        institutionalFields.forEach(f => f.style.display = 'none');

        phoneFields.forEach(f => f.style.display = 'none');
        
        googleFields.forEach(f => f.style.display = 'none');

    }

    authSelect.addEventListener('change', () => {
        resetFields();

        if (authSelect.value === 'org_id') {
            institutionalFields.forEach(f => f.style.display = 'block');
        }

        if (authSelect.value === 'phone') {
            phoneFields.forEach(f => f.style.display = 'block');
        }

        if (authSelect.value === 'google') {
            googleFields.forEach(f => f.style.display = 'block');
        }
    });

    resetFields();
});






document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Error toasts
    if (window.toastErrors && window.toastErrors.length) {
        window.toastErrors.forEach(message => createToast(message, 'error'));
    }

    // Success toast
    if (window.toastSuccess) {
        createToast(window.toastSuccess, 'success');
    }

    function createToast(message, type = 'error') {
        const toast = document.createElement('div');
        toast.className = 'toast' + (type === 'success' ? ' toast--success' : '');
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast__close" aria-label="Close">&times;</button>
        `;
        container.appendChild(toast);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-6px)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        // Manual close
        toast.querySelector('.toast__close').addEventListener('click', () => toast.remove());
    }
});