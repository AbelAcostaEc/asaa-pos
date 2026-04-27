import { http } from '../../core/http';

export function createRoleApi(endpoints = {}) {
    const collectionUrl = endpoints.collection ?? '/administration/roles';
    const resourceUrl = endpoints.resource ?? '/administration/roles';

    return {
        create(payload, options = {}) {
            return http.post(collectionUrl, {
                ...options,
                body: payload,
            });
        },

        update(id, payload, options = {}) {
            return http.post(`${resourceUrl}/${id}`, {
                ...options,
                body: payload,
            });
        },

        deleteItem(id, options = {}) {
            return http.delete(`${resourceUrl}/${id}`, options);
        },
    };
}
