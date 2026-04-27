import { http } from '../../core/http';

export function createUserApi(endpoints = {}) {
    const collectionUrl = endpoints.collection ?? '/administration/users';
    const resourceUrl = endpoints.resource ?? '/administration/users';

    return {
        fetchById(id, options = {}) {
            return http.get(`${resourceUrl}/${id}`, options);
        },

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

        toggleStatus(id, options = {}) {
            return http.patch(`${resourceUrl}/${id}/toggle`, options);
        },
    };
}
