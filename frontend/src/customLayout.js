import React from 'react';
import {AppBar, Layout} from 'react-admin';
import Typography from '@material-ui/core/Typography';
import {withStyles} from '@material-ui/core/styles';

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

const UserData = props => {
    const user = JSON.parse(localStorage.user);

    return (
        <div>
            <div>{user.username}</div>
            <div>{user.account.balance} PW</div>
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
