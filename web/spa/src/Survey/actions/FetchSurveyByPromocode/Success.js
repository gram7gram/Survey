"use strict";
import {FETCH_SURVEY_BY_PROMOCODE_SUCCESS} from '../../actions'
import {parseSurveyOnSuccess} from '../../utils';

export default function action(model) {
    return {
        type: FETCH_SURVEY_BY_PROMOCODE_SUCCESS,
        payload: parseSurveyOnSuccess(model)
    }
}