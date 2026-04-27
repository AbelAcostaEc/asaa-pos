const normalizePermissions = (permissions = []) =>
    permissions
        .map((permission) =>
            typeof permission === 'string' ? permission : permission?.name,
        )
        .filter(Boolean);

export const createEmptyRoleForm = () => ({
    id: null,
    name: '',
    permissions: [],
});

export const mapRoleToListItem = (role = {}) => ({
    id: role.id ?? null,
    name: role.name ?? '',
    permissions: normalizePermissions(role.permissions),
    isProtected: role.isProtected ?? role.name === 'Super Admin',
});

export const mapRolesToLookup = (roles = []) =>
    roles.reduce((lookup, role) => {
        const mappedRole = mapRoleToListItem(role);

        if (mappedRole.id != null) {
            lookup[mappedRole.id] = mappedRole;
        }

        return lookup;
    }, {});

export const mapRoleDetailToForm = (data = {}) => {
    const role = data.role ?? data;

    return {
        id: role.id ?? null,
        name: role.name ?? '',
        permissions: normalizePermissions(role.permissions),
    };
};

export const mapRoleFormToListItem = ({ formData, currentItem, responseData }) => ({
    ...(currentItem ?? {}),
    ...mapRoleToListItem({
        id: responseData?.role?.id ?? formData.id,
        name: formData.name,
        permissions: formData.permissions,
        isProtected: currentItem?.isProtected ?? formData.name === 'Super Admin',
    }),
});

export const buildRolePayload = ({ formData, editMode }) => {
    const payload = new FormData();

    payload.append('name', formData.name ?? '');

    (formData.permissions ?? []).forEach((permission, index) => {
        payload.append(`permissions[${index}]`, permission);
    });

    if (editMode) {
        payload.append('_method', 'PUT');
    }

    return payload;
};
