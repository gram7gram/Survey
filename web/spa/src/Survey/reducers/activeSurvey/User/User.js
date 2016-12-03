"use strict";
import * as Actions from '../../../actions'
import {combineReducers} from 'redux';

import individual from './Individual'

const email = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.USER_CHANGED:
            let change = action.payload.email
            if (change !== undefined) {
                return change;
            }
            return previousState;
        default:
            return previousState;
    }

}

export default combineReducers({
    email,
    individual,
})