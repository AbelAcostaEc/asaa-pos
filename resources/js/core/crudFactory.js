import { HttpError } from './http';

const createDefaultFilters = (filters = {}) => ({
    search: filters.search ?? '',
    per_page: filters.per_page ?? 10,
});

const buildFilterUrl = (filters) => {
    const url = new URL(window.location.href);
    url.searchParams.set('search', filters.search ?? '');
    url.searchParams.set('per_page', filters.per_page ?? 10);
    url.searchParams.set('page', 1);
    return url.toString();
};

const resolveErrorMessage = (error, fallbackMessage) => {
    if (error instanceof HttpError) {
        return error.data?.message || error.message || fallbackMessage;
    }

    return fallbackMessage;
};

const resolveValidationErrors = (error) => {
    if (error instanceof HttpError && error.status === 422) {
        return error.data?.errors || {};
    }

    return {};
};

const defaultNotify = ({ message, type = 'success' }) => {
    if (!message || typeof window.dispatchToast !== 'function') {
        return;
    }

    window.dispatchToast({ type, message });
};

export function createCrudFactory(config = {}) {
    const {
        api,
        createEmptyForm,
        mapDetailToForm,
        buildPayload,
        initialItemsById = {},
        filters = {},
        messages = {},
        modalNames = {},
        behavior = {},
        syncItemAfterUpdate = null,
        syncItemAfterToggle = null,
        notify = defaultNotify,
    } = config;

    const resolvedModalNames = {
        form: modalNames.form ?? 'crud-form-modal',
        confirm: modalNames.confirm ?? 'crud-confirm-modal',
    };

    const resolvedBehavior = {
        reloadAfterCreate: true,
        reloadAfterUpdate: false,
        reloadAfterToggle: false,
        reloadDelay: 800,
        ...behavior,
    };

    return {
        editMode: false,
        loading: false,
        errors: {},
        formData: createEmptyForm(),
        filters: createDefaultFilters(filters),
        messages: {
            invalidResponse: messages.invalidResponse ?? 'Unexpected server response.',
            loadError: messages.loadError ?? messages.saveError ?? 'Failed to load record.',
            saveError: messages.saveError ?? 'Failed to save record.',
            toggleError: messages.toggleError ?? messages.saveError ?? 'Failed to update status.',
        },
        confirmAction: {
            id: null,
            isActive: true,
        },
        itemsById: { ...initialItemsById },
        _searchTimer: null,

        init() {
            this.$watch('filters.search', () => {
                clearTimeout(this._searchTimer);
                this._searchTimer = window.setTimeout(() => this.applyFilters(), 600);
            });
        },

        applyFilters() {
            window.location.href = buildFilterUrl(this.filters);
        },

        resetForm() {
            this.formData = createEmptyForm();
            this.errors = {};
        },

        refreshPage(delay = resolvedBehavior.reloadDelay) {
            window.setTimeout(() => {
                window.location.assign(window.location.href);
            }, delay);
        },

        notify(message, type = 'success') {
            notify({ message, type });
        },

        upsertItem(item) {
            if (!item || item.id == null) {
                return;
            }

            this.itemsById = {
                ...this.itemsById,
                [item.id]: item,
            };
        },

        openCreateModal() {
            this.editMode = false;
            this.loading = false;
            this.resetForm();
            this.$dispatch('open-modal', resolvedModalNames.form);
        },

        async openEditModal(id) {
            this.loading = true;
            this.editMode = true;
            this.errors = {};

            try {
                const data = await api.fetchById(id, {
                    fallbackMessage: this.messages.invalidResponse,
                });

                this.formData = mapDetailToForm(data);
                this.$dispatch('open-modal', resolvedModalNames.form);
            } catch (error) {
                console.error(error);
                this.notify(resolveErrorMessage(error, this.messages.loadError), 'danger');
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            try {
                const payload = buildPayload({
                    formData: this.formData,
                    editMode: this.editMode,
                });

                const data = this.editMode
                    ? await api.update(this.formData.id, payload, {
                          fallbackMessage: this.messages.invalidResponse,
                      })
                    : await api.create(payload, {
                          fallbackMessage: this.messages.invalidResponse,
                      });

                this.$dispatch('close-modal', resolvedModalNames.form);
                this.notify(data.message);

                if (this.editMode && typeof syncItemAfterUpdate === 'function') {
                    this.upsertItem(
                        syncItemAfterUpdate({
                            currentItem: this.itemsById[this.formData.id],
                            formData: this.formData,
                            responseData: data,
                        }),
                    );
                }

                if (
                    (!this.editMode && resolvedBehavior.reloadAfterCreate) ||
                    (this.editMode && resolvedBehavior.reloadAfterUpdate)
                ) {
                    this.refreshPage();
                }

                return data;
            } catch (error) {
                console.error(error);
                this.errors = resolveValidationErrors(error);

                if (Object.keys(this.errors).length === 0) {
                    this.notify(resolveErrorMessage(error, this.messages.saveError), 'danger');
                }
            } finally {
                this.loading = false;
            }
        },

        confirmDisable(id, isActive) {
            this.confirmAction = {
                id,
                isActive,
            };

            this.$dispatch('open-modal', resolvedModalNames.confirm);
        },

        async executeToggle() {
            try {
                const data = await api.toggleStatus(this.confirmAction.id, {
                    fallbackMessage: this.messages.invalidResponse,
                });

                if (typeof syncItemAfterToggle === 'function') {
                    this.upsertItem(
                        syncItemAfterToggle({
                            id: this.confirmAction.id,
                            currentItem: this.itemsById[this.confirmAction.id],
                            previousState: this.confirmAction,
                            responseData: data,
                        }),
                    );
                }

                this.$dispatch('close-modal', resolvedModalNames.confirm);
                this.notify(data.message);

                if (resolvedBehavior.reloadAfterToggle) {
                    this.refreshPage();
                }

                return data;
            } catch (error) {
                console.error(error);
                this.notify(resolveErrorMessage(error, this.messages.toggleError), 'danger');
            }
        },
    };
}
