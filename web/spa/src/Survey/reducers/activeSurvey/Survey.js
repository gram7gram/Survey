"use strict";
import {combineReducers} from 'redux';
import keyBy from 'lodash/keyBy'

import * as Utils from '../../utils'
import * as Action from '../../actions';
import model from './model'
import validator from './Validator';

const canValidate = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.INDIVIDUAL_CHANGED:
        case Action.USER_CHANGED:
        case Action.CONTACTS_CHANGED:
        case Action.CITY_CHANGED:
        case Action.CLIENT_CHOICE_CHANGED:
        case Action.QUESTION_CHOICE_CLIENT_CHANGED:
            return true
        default:
            return false;
    }
}

const canShowRules = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.TOGGLE_RULES:
            return action.payload
        default:
            return previousState;
    }
}

const isDenied = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.UNAUTHORIZED_ERROR:
            return true;
        default:
            return false;
    }
}

const isSaving = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.CLIENT_SURVEY_COMPLETING:
            return true;
        case Action.CLIENT_SURVEY_COMPLETE_SUCCESS:
        case Action.CLIENT_SURVEY_COMPLETE_FAILURE:
            return false;
        default:
            return previousState;
    }
}

const isSaved = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.CLIENT_SURVEY_COMPLETE_SUCCESS:
            return true;
        case Action.CLIENT_SURVEY_COMPLETING:
        case Action.CLIENT_SURVEY_COMPLETE_FAILURE:
            return false;
        default:
            return previousState;
    }
}

const isLoading = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.FETCHING_SURVEY:
            return true;
        case Action.FETCH_SURVEY_BY_PROMOCODE_FAILURE:
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return false;
        default:
            return previousState;
    }
}

const isFound = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_FAILURE:
            return false;
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return true;
        default:
            return previousState;
    }
}

const areRulesAccepted = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.TOGGLE_ACCEPT_RULES:
            return action.payload
        default:
            return previousState;
    }
}

const currentQuestionIndex = (previousState = 0, action = {}) => {
    switch (action.type) {
        case Action.CLIENT_QUESTION_ANSWERED:
            return ++previousState;
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return 0;
        default:
            return previousState;
    }
}

const questions = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            let questions = action.payload.questions;
            if (!questions) {
                return previousState
            }

            if (Array.isArray(questions)) {
                return keyBy(questions, 'cid')
            }

            return questions
        default:
            return previousState;
    }
}

const questionOrder = (previousState = [], action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            let questions = action.payload.questions;
            let order = {};

            Utils.objectValues(questions).forEach(q => {
                order[q.order] = q.cid
            })

            return Utils.objectValues(order)
        default:
            return previousState;
    }
}

const survey = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            return action.payload
        default:
            return previousState;
    }
}

const respondentChoices = (previousState = {}, action = {}) => {
    switch (action.type) {
        case Action.FETCH_SURVEY_BY_PROMOCODE_SUCCESS:
            let choices = {};
            Utils.objectValues(action.payload.questions)
                .filter(q => q.isRespondentAnswerAllowed)
                .forEach(q => {
                    choices[q.cid] = {
                        cid: Utils.cid(),
                        name: null
                    }
                });
            return choices
        case Action.QUESTION_CHOICE_CLIENT_CHANGED:
            let cid = action.payload.question.cid;
            let change = Object.assign({}, previousState[cid], action.payload.change)
            return {
                ...previousState,
                [cid]: change
            }
        default:
            return previousState
    }
}

const completedSurvey = (previousState = false, action = {}) => {
    switch (action.type) {
        case Action.CLIENT_SURVEY_COMPLETE_SUCCESS:
            return action.payload;
        case Action.CLIENT_SURVEY_COMPLETING:
        case Action.CLIENT_SURVEY_COMPLETE_FAILURE:
            return false;
        default:
            return previousState
    }
}

const globalErrors = (previousState = [], action = {}) => {
    switch (action.type) {
        case Action.CLIENT_SURVEY_COMPLETE_FAILURE:
            switch (action.payload.params) {
                case 404:
                    return [
                        'Анкета не была найдена'
                    ]
                case 422:
                    return [
                        'Сохранение невозможно из-за неправильно заполненных данных'
                    ]
                case 500:
                    return [
                        'Сервер не смог обработать запрос. Сохранение невозможно'
                    ]
                default:
                    return [
                        'Непредвиденная ошибка. Попробуйте обновить страницу и повторить заполнение анкеты'
                    ]
            }
        default:
            return previousState
    }
}

export default combineReducers({
    completedSurvey,
    globalErrors,
    questionOrder,
    respondentChoices,
    model,
    survey,
    questions,
    canValidate,
    areRulesAccepted,
    isFound,
    isSaving,
    isSaved,
    isDenied,
    isLoading,
    currentQuestionIndex,
    canShowRules,
    validator,
})