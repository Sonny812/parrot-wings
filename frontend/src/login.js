import {Link, Login, LoginForm} from 'react-admin';
import {makeStyles} from '@material-ui/core/styles';
import React from "react";

const useStyles = makeStyles({
    'link-wrapper': {
        'padding': '16px 190px',
    },
    link: {
        'margin-top': '10px'
    }
});

export default () => {
    const classes = useStyles();

    return (
        <Login>
            <LoginForm/>
            <div className={classes["link-wrapper"]}>
                <Link className={classes.link} to='register'>Create a new account</Link>
            </div>
        </Login>
    )
};
