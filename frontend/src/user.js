import * as React from "react";
import {
    List,
    Datagrid,
    TextField,
    ShowButton,
    SimpleShowLayout,
    Show,
    NumberField,
    EmailField,
    BooleanField,
    Edit,
    SimpleForm,
    TextInput,
    BooleanInput,
    NumberInput,
    EditButton,
    Toolbar,
    SaveButton,
    useNotify,
    Filter,
} from 'react-admin';

const UserFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="q" alwaysOn/>
        <NumberInput label="Balance from" source="balance.from"/>
        <NumberInput label="Balance to" source="balance.to"/>
    </Filter>
);


export const UserList = (props) => {
    return (
        <List {...props} filters={<UserFilter/>}>
            <Datagrid>
                <TextField source="id"/>
                <TextField source="username"/>
                <EmailField source="email"/>
                <NumberField label="Account balance" source="account.balance"/>
                <ShowButton/>
                <EditButton/>
            </Datagrid>
        </List>
    );
};

export const UserShow = (props) => (
    <Show {...props}>
        <SimpleShowLayout>
            <TextField source="id"/>
            <TextField source="username"/>
            <EmailField source="email"/>
            <NumberField label="Account balance" source="account.balance"/>
            <BooleanField source="blocked"/>
        </SimpleShowLayout>
    </Show>
);

const UserEditToolbar = (props) => (
    <Toolbar {...props} >
        <SaveButton transform={data => {
            delete data.account;
            delete data.id;

            return data;
        }}/>
    </Toolbar>
);

export const UserEdit = (props) => {
        const notify = useNotify();

        const onFailure = (err) => {
            notify(err.body.errors.map(error => error.text).join(), 'error');
        }

        return (
            <Edit {...props} onFailure={onFailure} undoable={false}>
                <SimpleForm toolbar={<UserEditToolbar/>}>
                    <TextField source="id"/>
                    <TextInput placeholder="Name`" source="username"/>
                    <TextInput source="email" type="email"/>
                    <BooleanInput source="blocked"/>
                </SimpleForm>
            </Edit>
        );
    }
;
