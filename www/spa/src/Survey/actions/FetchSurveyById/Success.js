"use strict";
import {FETCH_SURVEY_SUCCESS} from '../../actions'
import {parseSurveyOnSuccess} from '../../utils';

export default function action(model) {
    return {
        type: FETCH_SURVEY_SUCCESS,
        payload: Array.isArray(model.questions) ? parseSurveyOnSuccess(model) : model
    }
}