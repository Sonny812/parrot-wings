import React from 'react';
import {Admin, Resource, ListGuesser,} from 'react-admin';
import {Route} from 'react-router-dom';
import Login from "./login";
import jsonServerProvider from 'ra-data-json-server'
import authProvider from './authProvider';
import Register from './register'

const App = () => (
    <Admin
        authProvider={authProvider}
        loginPage={Login}
        dataProvider={jsonServerProvider(process.env.REACT_APP_API_URL)}
        customRoutes={[
            <Route exact path="/register" component={Register} noLayout/>
        ]}
    >
        <Resource name='users' list={ListGuesser}/>
    </Admin>
);

export default App;
