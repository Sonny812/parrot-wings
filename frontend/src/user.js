import * as React from "react";
import {
    List,
    Datagrid,
    TextField,
    DateField,
    ShowButton,
    SimpleShowLayout,
    Show,
    NumberField,
    EmailField,
    Create,
    SimpleForm,
    NumberInput,
    ReferenceInput,
    AutocompleteInput,
    required,
    minValue,
    useNotify
} from 'react-admin';

export const UserList = (props) => {
    return (
        <List {...props}>
            <Datagrid>
                <TextField source="id"/>
                <TextField source="username"/>
                <EmailField source="email"/>
                <NumberField label="Account balance" source="account.balance"/>
                <ShowButton/>
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
        </SimpleShowLayout>
    </Show>
);
