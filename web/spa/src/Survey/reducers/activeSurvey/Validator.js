"use strict";
import * as Action from '../../actions';

const inital = {
    total: 0,
    count: 0,
    messages: [],
    errors: {}
}

export default (previousState = inital, action = {}) => {
    switch (action.type) {
        case Action.SURVEY_VALIDATION_FAILED:
            return action.payload
        case Action.SURVEY_VALIDATION_SUCCESS:
            return inital
        default:
            return previousState;
    }
}
