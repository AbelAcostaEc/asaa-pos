const normalizeRoles = (roles = []) =>
    roles
        .map((role) => (typeof role === 'string' ? role : role?.name))
        .filter(Boolean);

const normalizeBoolean = (value) => {
    if (typeof value === 'string') {
        return value === 'true' || value === '1';
    }

    return Boolean(value);
};

const buildInitial = (name = '') => name.trim().charAt(0).toUpperCase() || '?';

export const createEmptyUserForm = () => ({
    id: null,
    name: '',
    email: '',
    password: '',
    roles: [],
});

export const mapUserResponseToForm = (responseData = {}) => {
    const user = responseData.user ?? {};

    return {
        id: user.id ?? null,
        name: user.name ?? '',
        email: user.email ?? '',
        password: '',
        roles: normalizeRoles(user.roles),
    };
};

export const mapUserToListItem = (user = {}) => ({
    id: user.id ?? null,
    name: user.name ?? '',
    email: user.email ?? '',
    roles: normalizeRoles(user.roles),
    isActive: normalizeBoolean(user.isActive ?? user.is_active),
    initial: buildInitial(user.name ?? ''),
});

export const mapUsersToLookup = (users = []) =>
    users.reduce((lookup, user) => {
        const mappedUser = mapUserToListItem(user);

        if (mappedUser.id != null) {
            lookup[mappedUser.id] = mappedUser;
        }

        return lookup;
    }, {});

export const mapUserFormToListItem = ({ formData, currentItem }) => ({
    ...(currentItem ?? {}),
    ...mapUserToListItem({
        id: formData.id,
        name: formData.name,
        email: formData.email,
        roles: formData.roles,
        is_active: currentItem?.isActive ?? true,
    }),
});

export const mapToggleResponseToListItem = ({ currentItem, responseData, id }) => ({
    ...(currentItem ?? {}),
    id,
    isActive: normalizeBoolean(responseData.is_active ?? currentItem?.isActive),
});

export const buildUserPayload = ({ formData, editMode }) => {
    const payload = new FormData();

    payload.append('name', formData.name ?? '');
    payload.append('email', formData.email ?? '');

    if (formData.password) {
        payload.append('password', formData.password);
    }

    (formData.roles ?? []).forEach((role, index) => {
        payload.append(`roles[${index}]`, role);
    });

    if (editMode) {
        payload.append('_method', 'PUT');
    }

    return payload;
};
