"use strict";
import {combineReducers} from 'redux';

import * as Action from '../../actions';

const question = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.CLIENT_QUESTION_ANSWERED:
        case Action.CLIENT_CHOICE_CHANGED:
            let question = action.payload.question;
            return {
                ...question
            };
        default:
            return previousState;
    }
}

const choices = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.QUESTION_CHOICE_CLIENT_CHANGED:
            let choices = {...previousState}
            let cid = action.payload.choice.cid;
            let choice = choices[cid] || null
            if (!choice) {
                return previousState
            }
            let change = action.payload.change
            choices[cid] = {
                ...choice,
                ...change
            }
            return choices;
        case Action.CLIENT_QUESTION_ANSWERED:
        case Action.CLIENT_CHOICE_CHANGED:
            return action.payload.choices
        default:
            return previousState;
    }
}

export default combineReducers({
    question,
    choices
})