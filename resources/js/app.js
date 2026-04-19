import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        items: [],

        push(payload = {}) {
            const id = Date.now() + Math.random();
            const toast = {
                id,
                type: payload.type || 'success',
                title: payload.title || '',
                message: payload.message || '',
                duration: payload.duration ?? 3200,
                createdAt: Date.now(),
            };

            this.items.push(toast);

            if (toast.duration > 0) {
                window.setTimeout(() => this.remove(id), toast.duration);
            }
        },

        remove(id) {
            this.items = this.items.filter((item) => item.id !== id);
        },
    });
});

window.dispatchToast = (payload) => {
    window.dispatchEvent(new CustomEvent('app-toast', { detail: payload }));
};

window.notifySuccess = (message, title = '') => {
    window.dispatchToast({ type: 'success', title, message });
};

window.notifyError = (message, title = '') => {
    window.dispatchToast({ type: 'danger', title, message });
};

window.notifyWarning = (message, title = '') => {
    window.dispatchToast({ type: 'warning', title, message });
};

window.notifyInfo = (message, title = '') => {
    window.dispatchToast({ type: 'info', title, message });
};

Alpine.start();
