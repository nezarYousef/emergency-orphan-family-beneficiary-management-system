document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('#app-sidebar');
    const toggle = document.querySelector('.sidebar-toggle');

    toggle?.addEventListener('click', () => {
        const open = sidebar?.classList.toggle('is-open') ?? false;
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    });

    document.querySelectorAll('[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => form.classList.add('is-loading'));
    });

    document.querySelectorAll('form:not([data-no-loading])').forEach((form) => {
        form.addEventListener('submit', () => {
            const submit = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submit && form.method.toLowerCase() !== 'get') {
                submit.textContent = 'Saving…';
                submit.setAttribute('aria-busy', 'true');
                submit.disabled = true;
            }
        });
    });
});
