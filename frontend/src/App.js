import React from 'react';
import {Admin, Resource, fetchUtils} from 'react-admin';
import {Route} from 'react-router-dom';
import Login from "./login";
import myJsonServerProvider from "./myJsonServerProvider";
import authProvider from './authProvider';
import Register from './register'
import CustomLayout from './customLayout';
import {TransactionList, TransactionShow, TransactionCreate} from "./transaction";
import {UserEdit, UserList, UserShow} from "./user";

const httpClient = (url, options = {}) => {
    if (!options.headers) {
        options.headers = new Headers({Accept: 'application/json'});
    }

    const user = localStorage.getItem('user');
    if (user) {
        const {token} = JSON.parse(user);
        options.headers.set('X-AUTH-TOKEN', token);
    }
    return fetchUtils.fetchJson(url, options);
};

const App = () => (
    <Admin
        authProvider={authProvider}
        loginPage={Login}
        layout={CustomLayout}
        dataProvider={myJsonServerProvider(process.env.REACT_APP_API_URL, httpClient)}
        customRoutes={[
            <Route exact path="/register" component={Register} noLayout/>
        ]}
    >
        {
            permissions => [
                <Resource name='transaction'
                          create={!permissions || !permissions.includes('ROLE_ADMIN') ? TransactionCreate : null}
                          show={TransactionShow}
                          list={TransactionList}/>,
                <Resource name='user'
                          list={permissions && permissions.includes('ROLE_ADMIN') ? UserList : null}
                          show={permissions && permissions.includes('ROLE_ADMIN') ? UserShow : null}
                          edit={permissions && permissions.includes('ROLE_ADMIN') ? UserEdit : null}
                />
            ]
        }
    </Admin>
);

export default App;
