import React, {useState} from 'react';
import {AppBar, Layout} from 'react-admin';
import Typography from '@material-ui/core/Typography';
import {withStyles} from '@material-ui/core/styles';
import useInterval from "@use-it/interval";

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

const UserBalance = ({delay}) => {
    const user = JSON.parse(localStorage.user);

    const [balance, setBalance] = useState(user.account.balance);

    const fetchConfig = {headers: {'X-AUTH-TOKEN': user.token}};

    useInterval(() => {
        fetch(process.env.REACT_APP_API_URL + '/balance', fetchConfig)
            .then(response => response.json())
            .then(data => {
                const {balance} = data;
                setBalance(balance);
                user.account.balance = balance;
                localStorage.setItem('user', JSON.stringify(user))
            });

    }, delay);

    return <div>{balance}</div>
}

const UserData = props => {
    const user = JSON.parse(localStorage.user);

    return (
        <div>
            <div>{user.username}</div>
            <UserBalance delay={3000}/>
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
