"use strict";
import {combineReducers} from 'redux';
import * as Actions from '../../../actions'

const mobilePhone = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.CONTACTS_CHANGED:
            return action.payload.number
        default:
            return previousState;
    }
}

export default combineReducers({
    mobilePhone
})