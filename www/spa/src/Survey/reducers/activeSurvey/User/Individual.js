"use strict";
import * as Actions from '../../../actions'

import {combineReducers} from 'redux';
import contacts from './Contacts';
import address from './Address';

const lastName = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.INDIVIDUAL_CHANGED:
            let change = action.payload.lastName
            if (change !== undefined) {
                return change;
            }
            return previousState;
        default:
            return previousState;
    }
}

const firstName = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.INDIVIDUAL_CHANGED:
            let change = action.payload.firstName
            if (change !== undefined) {
                return change;
            }
            return previousState;
        default:
            return previousState;
    }
}

const age = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.INDIVIDUAL_CHANGED:
            let change = action.payload.age
            if (change !== undefined) {
                return parseInt(change)
            }
            return previousState;
        default:
            return previousState;
    }
}

export default combineReducers({
    contacts,
    address,
    lastName,
    firstName,
    age,
})