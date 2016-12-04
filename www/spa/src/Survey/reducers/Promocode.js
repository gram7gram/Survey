"use strict";
import * as Action from '../actions';
import {combineReducers} from 'redux';

const value = (previousState = SurveyModel.promocode, action = {}) => {

    switch (action.type) {
        case Action.PROMOCODE_CHANGED:
            return action.payload.trim()
        default:
            return previousState
    }
}

const validatePromocode = (code) => {
    return code && code.length === 6;
}

const isValid = (previousState = validatePromocode(SurveyModel.promocode), action = {}) => {

    switch (action.type) {
        case Action.PROMOCODE_CHANGED:
            return validatePromocode(action.payload)
        default:
            return previousState
    }
}

const isVerified = (previousState = false, action = {}) => {

    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return true
        case Action.FETCH_SURVEY_BY_PROMOCODE_FAILURE:
            return false
        default:
            return previousState
    }
}

export default  combineReducers({
    isVerified,
    isValid,
    value,
})
