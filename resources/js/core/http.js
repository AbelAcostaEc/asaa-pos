export class HttpError extends Error {
    constructor(message, { status = 500, data = {}, response = null } = {}) {
        super(message);
        this.name = 'HttpError';
        this.status = status;
        this.data = data;
        this.response = response;
    }
}

export const getCsrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? '';

export const buildHeaders = (headers = {}) => ({
    'X-CSRF-TOKEN': getCsrfToken(),
    Accept: 'application/json',
    ...headers,
});

export const parseJsonSafely = async (response) => {
    const contentType = response.headers.get('content-type') || '';

    if (!contentType.includes('application/json')) {
        return null;
    }

    try {
        return await response.json();
    } catch {
        return null;
    }
};

export const request = async (url, options = {}) => {
    const {
        method = 'GET',
        headers = {},
        body,
        fallbackMessage = 'Unexpected server response.',
    } = options;

    const response = await fetch(url, {
        method,
        headers: buildHeaders(headers),
        body,
    });

    const data = (await parseJsonSafely(response)) ?? {
        message: fallbackMessage,
    };

    if (!response.ok) {
        throw new HttpError(data.message || fallbackMessage, {
            status: response.status,
            data,
            response,
        });
    }

    return data;
};

export const http = {
    get(url, options = {}) {
        return request(url, { ...options, method: 'GET' });
    },

    post(url, options = {}) {
        return request(url, { ...options, method: 'POST' });
    },

    patch(url, options = {}) {
        return request(url, { ...options, method: 'PATCH' });
    },
};
