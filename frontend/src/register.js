import React from "react";
import {Card, TextField} from '@material-ui/core';
import {makeStyles} from "@material-ui/core/styles";
import {Field, Form} from 'react-final-form';
import CardActions from "@material-ui/core/CardActions";
import Button from "@material-ui/core/Button";
import CircularProgress from "@material-ui/core/CircularProgress";
import {useNotify, useSafeSetState} from "ra-core";
import {Notification} from "react-admin"
import {useHistory} from "react-router-dom"


const useStyles = makeStyles({
        main: {
            display: 'flex',
            flexDirection: 'column',
            minHeight: '100vh',
            height: '1px',
            alignItems: 'center',
            justifyContent: 'flex-start',
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            backgroundImage:
                'radial-gradient(circle at 50% 14em, #313264 0%, #00023b 60%, #00023b 100%)',
        },
        card: {
            minWidth: 300,
            marginTop: '8em',
        },
        avatar: {
            margin: '1em',
            display: 'flex',
            justifyContent: 'center',
        },
        form: {
            padding: '0 1em 1em 1em',
        },
        input: {
            marginTop: '1em',
        },
        button: {
            width: '100%',
        },
    },
    {name: 'RaRegister'}
);

const Input = ({
                   meta: {touched, error}, // eslint-disable-line react/prop-types
                   input: inputProps, // eslint-disable-line react/prop-types
                   ...props
               }) => (
    <TextField
        error={!!(touched && error)}
        helperText={touched && error}
        {...inputProps}
        {...props}
        fullWidth
    />
);


const Register = (props) => {
        const history = useHistory();
        const classes = useStyles();
        const [loading, setLoading] = useSafeSetState(false);
        const notify = useNotify();

        const validate = (values) => {

            const errors = {
                name: undefined,
                email: undefined,
                password: undefined,
                repeat_password: undefined,
            };

            if (!values.name) {
                errors.name = 'This value should not be blank';
            }

            if (!values.email) {
                errors.email = 'This value should not be blank';
            }

            if (!values.password) {
                errors.password = 'Enter a password';
            }

            if (values.password?.length < 6) {
                errors.password = 'Your password is too short'
            }

            if (!values.repeat_password) {
                errors.repeat_password = 'Repeat your password';
            }

            if (values.password !== values.repeat_password) {
                errors.repeat_password = 'Passwords do not match'
            }

            return errors;
        };

        const onSubmit = values => {
            const request = {
                method: 'POST',
                body: JSON.stringify({
                    username: values.name,
                    email: values.email,
                    rawPassword: values.password,
                })
            }

            setLoading(true);

            fetch(process.env.REACT_APP_API_URL + '/register', request)
                .then(response => response.json().then(data => ({status: response.status, data: data})))
                .then(response => {
                    console.log(response);
                    if (response.status < 200 || response.status >= 300) {
                        const errorText = response.data.errors.map(error => error.text).join();

                        notify(errorText, 'error');
                    } else {
                        notify('Your account successfully created. Enter your credentials to login');
                        history.push("/login");
                    }
                })
                .finally(setLoading(false));
        }

        return (
            <div className={classes.main}>
                <Card className={classes.card}>
                    <Form
                        onSubmit={onSubmit}
                        validate={validate}
                        render={({handleSubmit}) => (
                            <form onSubmit={handleSubmit}>
                                <div className="form">
                                    <div className="input">
                                        <Field
                                            autoFocus
                                            id="name"
                                            name="name"
                                            component={Input}
                                            label="Name"
                                            disabled={loading}
                                        />
                                    </div>

                                    <div className="input">
                                        <Field
                                            id="email"
                                            name="email"
                                            component={Input}
                                            label="E-mail"
                                            disabled={loading}
                                        />
                                    </div>
                                    <div className="input">
                                        <Field
                                            id="password"
                                            name="password"
                                            component={Input}
                                            label="Password"
                                            type="password"
                                            disabled={loading}
                                        />
                                    </div>
                                    <div className="input">
                                        <Field
                                            id="repeat_password"
                                            name="repeat_password"
                                            component={Input}
                                            label="Confirm your password"
                                            type="password"
                                            disabled={loading}
                                        />
                                    </div>
                                    <CardActions>
                                        <Button
                                            variant="contained"
                                            type="submit"
                                            color="primary"
                                            disabled={loading}
                                            className={classes.button}
                                        >
                                            {loading && (
                                                <CircularProgress
                                                    className={classes.icon}
                                                    size={18}
                                                    thickness={2}
                                                />
                                            )}
                                            Create an account
                                        </Button>
                                    </CardActions>
                                </div>
                            </form>
                        )}/>
                </Card>
                <Notification/>
            </div>
        )
    }
;

export default Register;

