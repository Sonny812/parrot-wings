import {AUTH_LOGIN, AUTH_CHECK, AUTH_LOGOUT, AUTH_ERROR} from 'react-admin';

export default (type, params) => {
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
                if (response.status < 200 || response.status >= 300) {
                    const errorText = response.data.errors.map(error => error.text).join();

                    return Promise.reject(errorText);
                }
                
                localStorage.setItem('token', response.token);
            });
    }

    if (type === AUTH_LOGOUT) {
        localStorage.removeItem('token');
        return Promise.resolve();
    }

    if (type === AUTH_ERROR) {
        const status = params.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('token');
            return Promise.reject();
        }
        return Promise.resolve();
    }

    if (type === AUTH_CHECK) {
        return localStorage.getItem('token') ? Promise.resolve() : Promise.reject();
    }

    return Promise.resolve();
}
