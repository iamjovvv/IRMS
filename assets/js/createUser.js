document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('roleSelect');

    const sections = {
        reporter: document.getElementById('reporterFields'),
        staff: document.getElementById('staffFields'),
        responder: document.getElementById('responderFields')
    };

    function hideAll() {
        Object.values(sections).forEach(section => {
            if (!section) return;
            section.classList.add('hidden');

            section.querySelectorAll('input').forEach(input => {
                input.required = false;
                input.value = '';
            });
        });
    }

    function show(role) {
        hideAll();

        if (!sections[role]) return;

        sections[role].classList.remove('hidden');

        sections[role].querySelectorAll('input').forEach(input => {
            input.required = true;
        });
    }

    roleSelect.addEventListener('change', () => {
        show(roleSelect.value);
    });

    // Initial state
    hideAll();
});



document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('toast-container');
    if (!container) return;

    function createToast(message, type = 'error') {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast__close" aria-label="Close">&times;</button>
        `;
        container.appendChild(toast);

        // Auto-remove after 5s
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-6px)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        toast.querySelector('.toast__close').addEventListener('click', () => toast.remove());
    }

    // Display errors
    if (window.accountErrors) {
        window.accountErrors.forEach(msg => createToast(msg, 'error'));
    }

    // Display success
    if (window.accountSuccess) {
        window.accountSuccess.forEach(msg => createToast(msg, 'success'));
    }
});