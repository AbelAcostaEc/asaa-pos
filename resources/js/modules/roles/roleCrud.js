import { createCrudFactory } from '../../core/crudFactory';
import { createRoleApi } from './roleApi';
import {
    buildRolePayload,
    createEmptyRoleForm,
    mapRoleDetailToForm,
    mapRoleFormToListItem,
    mapRolesToLookup,
} from './roleMapper';

const createFallbackRole = (id) => ({
    id,
    name: '',
    permissions: [],
    isProtected: false,
});

export default function roleCrud(config = {}) {
    const labels = {
        noPermissions: config.labels?.noPermissions ?? 'No permissions',
        protected: config.labels?.protected ?? 'Protected',
    };

    const permissionsLabelTemplates = {
        none: config.permissionsLabelTemplates?.none ?? 'No permissions',
        single:
            config.permissionsLabelTemplates?.single ?? '1 permission selected',
        multiple:
            config.permissionsLabelTemplates?.multiple ??
            '__count__ permissions selected',
    };

    const permissionCountTemplates = {
        none: config.permissionCountTemplates?.none ?? 'No permissions',
        single: config.permissionCountTemplates?.single ?? '1 permission',
        multiple:
            config.permissionCountTemplates?.multiple ?? '__count__ permissions',
    };

    const baseCrud = createCrudFactory({
        api: createRoleApi(config.endpoints),
        createEmptyForm: createEmptyRoleForm,
        mapDetailToForm: mapRoleDetailToForm,
        buildPayload: buildRolePayload,
        getEditData: ({ item }) => item ?? createFallbackRole(null),
        initialItemsById: mapRolesToLookup(config.initialRoles ?? []),
        filters: config.filters,
        messages: config.messages,
        modalNames: {
            form: 'role-modal',
            confirm: 'confirm-delete',
        },
        behavior: {
            reloadAfterCreate: true,
            reloadAfterUpdate: false,
            reloadAfterDelete: false,
            reloadDelay: 800,
        },
        syncItemAfterUpdate: mapRoleFormToListItem,
        removeItemAfterDelete: ({ id, itemsById, instance }) => {
            const nextItems = { ...itemsById };
            delete nextItems[id];
            instance.itemsById = nextItems;
        },
    });

    return {
        ...baseCrud,
        labels,
        permissionsLabelTemplates,

        roleItem(id) {
            return this.itemsById[id] ?? createFallbackRole(id);
        },

        roleExists(id) {
            return Boolean(this.itemsById[id]);
        },

        roleName(id) {
            return this.roleItem(id).name;
        },

        rolePermissions(id) {
            return this.roleItem(id).permissions ?? [];
        },

        rolePreviewPermissions(id, limit = 5) {
            return this.rolePermissions(id).slice(0, limit);
        },

        roleExtraPermissionsCount(id, limit = 5) {
            return Math.max(this.rolePermissions(id).length - limit, 0);
        },

        roleHasPermissions(id) {
            return this.rolePermissions(id).length > 0;
        },

        rolePermissionsCountLabel(id) {
            const count = this.rolePermissions(id).length;

            if (count === 0) {
                return permissionCountTemplates.none;
            }

            if (count === 1) {
                return permissionCountTemplates.single.replace('__count__', count);
            }

            return permissionCountTemplates.multiple.replace('__count__', count);
        },

        roleIsProtected(id) {
            return Boolean(this.roleItem(id).isProtected);
        },

        selectedPermissionsLabel() {
            const count = this.formData.permissions?.length || 0;

            if (count === 0) {
                return this.permissionsLabelTemplates.none;
            }

            if (count === 1) {
                return this.permissionsLabelTemplates.single;
            }

            return this.permissionsLabelTemplates.multiple.replace('__count__', count);
        },
    };
}
