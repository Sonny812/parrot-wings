import React from 'react';
import {Admin, Resource, fetchUtils, ListGuesser, ShowGuesser} from 'react-admin';
import {Route} from 'react-router-dom';
import Login from "./login";
import jsonServerProvider from 'ra-data-json-server'
import authProvider from './authProvider';
import Register from './register'
import CustomLayout from './customLayout';
import {TransactionList, TransactionShow, TransactionCreate} from "./transaction";
import {UserList, UserShow} from "./user";

const httpClient = (url, options = {}) => {
    if (!options.headers) {
        options.headers = new Headers({Accept: 'application/json'});
    }

    const {token} = JSON.parse(localStorage.getItem('user'));
    options.headers.set('X-AUTH-TOKEN', token);

    return fetchUtils.fetchJson(url, options);
};

const App = () => (
    <Admin
        authProvider={authProvider}
        loginPage={Login}
        layout={CustomLayout}
        dataProvider={jsonServerProvider(process.env.REACT_APP_API_URL, httpClient)}
        customRoutes={[
            <Route exact path="/register" component={Register} noLayout/>
        ]}
    >
        {
            permissions => [
                <Resource name='transaction'
                          create={!permissions.includes('ROLE_ADMIN') ? TransactionCreate : null}
                          show={TransactionShow}
                          list={TransactionList}/>,
                <Resource name='user'
                          list={permissions.includes('ROLE_ADMIN') ? UserList : null}
                          show={permissions.includes('ROLE_ADMIN') ? UserShow : null}
                />
            ]
        }
    </Admin>
);

export default App;
