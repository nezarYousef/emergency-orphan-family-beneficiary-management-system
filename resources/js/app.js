document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('#app-sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const mobile = window.matchMedia('(max-width: 991.98px)');

    if (sidebar && toggle) {
        const backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.append(backdrop);

        const setOpen = (open, restoreFocus = false) => {
            sidebar.classList.toggle('is-open', open);
            document.body.classList.toggle('sidebar-open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
            sidebar.inert = mobile.matches && !open;
            if (restoreFocus) toggle.focus();
        };

        toggle.addEventListener('click', () => {
            const open = !sidebar.classList.contains('is-open');
            setOpen(open);
            if (open) sidebar.querySelector('a, button')?.focus();
        });
        backdrop.addEventListener('click', () => setOpen(false, true));
        sidebar.querySelector('.sidebar-close')?.addEventListener('click', () => setOpen(false, true));
        document.addEventListener('keydown', (event) => {
            if (!mobile.matches || !sidebar.classList.contains('is-open')) return;
            if (event.key === 'Escape') setOpen(false, true);
            if (event.key === 'Tab') {
                const focusable = [...sidebar.querySelectorAll('a[href], button:not(:disabled)')];
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        });
        mobile.addEventListener('change', () => setOpen(false, mobile.matches && sidebar.contains(document.activeElement)));
        setOpen(false);
    }

    document.querySelectorAll('form:not([data-no-loading])').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (event.defaultPrevented || form.method.toLowerCase() === 'get') return;
            const submit = event.submitter ?? form.querySelector('button[type="submit"], input[type="submit"]');
            if (form.hasAttribute('data-loading-form')) form.classList.add('is-loading');
            if (submit) {
                const label = submit.dataset.loadingLabel ?? form.dataset.loadingLabel ?? document.body.dataset.savingLabel;
                if (label) {
                    if (submit instanceof HTMLInputElement) submit.value = label;
                    else submit.textContent = label;
                }
                submit.setAttribute('aria-busy', 'true');
            }
        });
    });
});
