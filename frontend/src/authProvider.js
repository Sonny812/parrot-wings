import {AUTH_LOGIN, AUTH_CHECK, AUTH_LOGOUT, AUTH_ERROR, AUTH_GET_PERMISSIONS} from 'react-admin';

const authProvider = (type, params) => {
    if (type === AUTH_LOGIN) {
        const {username, password} = params;
        const request = new Request(process.env.REACT_APP_API_URL + '/login', {
            method: 'POST',
            body: JSON.stringify({email: username, rawPassword: password}),
            headers: new Headers({'Content-Type': 'application/json'}),
        })

        return fetch(request)
            .then(response => response.json().then(data => ({status: response.status, data: data})))
            .then((response) => {
                const user = response.data;

                if (response.status < 200 || response.status >= 300) {
                    const errorText = user.errors.map(error => error.text).join();

                    return Promise.reject(errorText);
                }

                localStorage.setItem('user', JSON.stringify(user));
            });
    }

    if (type === AUTH_LOGOUT) {
        localStorage.removeItem('user');
        return Promise.resolve();
    }

    if (type === AUTH_ERROR) {
        const status = params.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('user');
            return Promise.reject();
        }
        return Promise.resolve();
    }

    if (type === AUTH_CHECK) {
        return localStorage.getItem('user') ? Promise.resolve() : Promise.reject();
    }

    if (type === AUTH_GET_PERMISSIONS) {
        const user = localStorage.getItem('user');
        const roles = JSON.parse(user)?.roles;

        return Promise.resolve(roles ?? ['ROLE_GUEST']);
    }

    return Promise.resolve();
}

export default authProvider;
