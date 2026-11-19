const menu = document.querySelector('[data-menu]');
menu?.addEventListener('click', () => {
    const open = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(open));
    document.getElementById('side-nav').classList.toggle('open', open);
});
document.addEventListener('submit', event => {
    const form = event.target;
    if (form.dataset.submitting) {
        event.preventDefault();
        return;
    }
    if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
        return;
    }
    if (form.method.toLowerCase() === 'post') {
        form.dataset.submitting = 'true';
        const button = event.submitter;
        if (button) button.setAttribute('aria-busy', 'true');
    }
});
const files = Array.from(document.querySelectorAll('[data-document]'));
files.forEach(input => input.addEventListener('change', () => {
    if (!input.files.length) return;
    files.filter(other => other !== input).forEach(other => { other.value = ''; });
    document.getElementById('chosen-file').textContent = input.files[0].name;
}));

window.addEventListener('pageshow', () => {
    document.querySelectorAll('form[data-submitting]').forEach(form => {
        delete form.dataset.submitting;
        form.querySelector('[aria-busy]')?.removeAttribute('aria-busy');
    });
});
