import React, {useState} from 'react';
import {AppBar, Layout} from 'react-admin';
import Typography from '@material-ui/core/Typography';
import {withStyles} from '@material-ui/core/styles';
import { EventSourcePolyfill } from 'event-source-polyfill';


const styles = {
    title: {
        flex: 1,
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap',
        overflow: 'hidden',
    },
    spacer: {
        flex: 1,
    },
};

const UserBalance = ({user}) => {
    const [balance, setBalance] = useState(user.account.balance);

    const fetchConfig = {headers: {'X-AUTH-TOKEN': user.token}};

    fetch(process.env.REACT_APP_API_URL + '/balance', fetchConfig)
        .then(r => r.json().then(json => ({headers: r.headers, json: json})))
        .then(obj => {
            const json = obj.json;
            const {balance, subscribeTopic, token} = json;

            setBalance(balance);

            user.account.balance = balance;
            localStorage.setItem('user', JSON.stringify(user));

            const hubUrl = obj.headers.get('Link').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];
            const hub = new URL(hubUrl);
            hub.searchParams.append('topic', subscribeTopic);

            const eventSource = new EventSourcePolyfill(hub, {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });
            eventSource.onmessage = event => setBalance(event.balance);
        });

    return <div>{balance}</div>
}

const UserData = props => {
    if (!localStorage.user) {
        return null;
    }

    const user = JSON.parse(localStorage.user);

    return (
        <div>
            <div>{user.username}</div>
            {user.account && <UserBalance delay={3000} user={user}/>}
        </div>
    )
};

const MyAppBar = withStyles(styles)(({classes, ...props}) => (
    <AppBar {...props}>
        <Typography
            variant="h6"
            color="inherit"
            className={classes.title}
            id="react-admin-title"
        />

        <span className={classes.spacer}/>
        <UserData/>
    </AppBar>
));

const MyLayout = (props) => <Layout {...props} appBar={MyAppBar}/>;


export default MyLayout;
