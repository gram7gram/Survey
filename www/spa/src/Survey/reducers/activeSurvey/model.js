"use strict";
import {combineReducers} from 'redux';

import * as Action from '../../actions';
import answersReducer from './Answers';
import user from './User/User';

const survey = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return action.payload;
        default:
            return previousState;
    }
}

const answers = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.QUESTION_CHOICE_CLIENT_CHANGED:
        case Action.CLIENT_CHOICE_CHANGED:
            let cid = action.payload.question.cid;
            let previousAnswers = previousState[cid] || {};

            return {
                ...previousState,
                [cid]: answersReducer({
                    question: action.payload.question,
                    choices: previousAnswers.choices
                }, action)
            }
        default:
            return previousState;
    }
}

export default combineReducers({
    user,
    survey,
    answers
})