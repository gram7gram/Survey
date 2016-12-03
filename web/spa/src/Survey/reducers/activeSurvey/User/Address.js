"use strict";
import {combineReducers} from 'redux';
import * as Actions from '../../../actions'

const city = (previousState = null, action = {}) => {
    switch (action.type) {
        case Actions.CITY_CHANGED:
            return action.payload.name
        default:
            return previousState;
    }
}

export default combineReducers({
    city
})