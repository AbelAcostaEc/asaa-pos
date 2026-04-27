import { createCrudFactory } from '../../core/crudFactory';
import { createUserApi } from './userApi';
import {
    buildUserPayload,
    createEmptyUserForm,
    mapToggleResponseToListItem,
    mapUserFormToListItem,
    mapUserResponseToForm,
    mapUsersToLookup,
} from './userMapper';

const createFallbackUser = (id) => ({
    id,
    name: '',
    email: '',
    roles: [],
    isActive: false,
    initial: '?',
});

export default function userCrud(config = {}) {
    const labels = {
        active: config.labels?.active ?? 'Active',
        inactive: config.labels?.inactive ?? 'Inactive',
        disabled: config.labels?.disabled ?? 'Disabled',
        enable: config.labels?.enable ?? 'Enable',
        disable: config.labels?.disable ?? 'Disable',
    };

    const baseCrud = createCrudFactory({
        api: createUserApi(config.endpoints),
        createEmptyForm: createEmptyUserForm,
        mapDetailToForm: mapUserResponseToForm,
        buildPayload: buildUserPayload,
        initialItemsById: mapUsersToLookup(config.initialUsers ?? []),
        filters: config.filters,
        messages: config.messages,
        modalNames: {
            form: 'user-modal',
            confirm: 'confirm-disable',
        },
        behavior: {
            reloadAfterCreate: true,
            reloadAfterUpdate: false,
            reloadAfterToggle: false,
            reloadDelay: 800,
        },
        syncItemAfterUpdate: mapUserFormToListItem,
        syncItemAfterToggle: mapToggleResponseToListItem,
    });

    return {
        ...baseCrud,
        labels,

        userItem(id) {
            return this.itemsById[id] ?? createFallbackUser(id);
        },

        userName(id) {
            return this.userItem(id).name;
        },

        userEmail(id) {
            return this.userItem(id).email;
        },

        userInitial(id) {
            return this.userItem(id).initial;
        },

        userRoles(id) {
            return this.userItem(id).roles ?? [];
        },

        userIsActive(id) {
            return this.userItem(id).isActive;
        },

        userTableStatusText(id) {
            return this.userIsActive(id) ? this.labels.active : this.labels.disabled;
        },

        userCardStatusText(id) {
            return this.userIsActive(id) ? this.labels.active : this.labels.inactive;
        },

        userStatusBadgeClass(id) {
            return this.userIsActive(id)
                ? 'bg-success/10 text-success'
                : 'bg-danger/10 text-danger';
        },

        userDesktopToggleClass(id) {
            return this.userIsActive(id)
                ? 'text-danger hover:text-danger/80'
                : 'text-success hover:text-success/80';
        },

        userMobileToggleClass(id) {
            return this.userIsActive(id)
                ? 'bg-danger/10 text-danger hover:bg-danger/20'
                : 'bg-success/10 text-success hover:bg-success/20';
        },

        userToggleActionText(id) {
            return this.userIsActive(id) ? this.labels.disable : this.labels.enable;
        },
    };
}
