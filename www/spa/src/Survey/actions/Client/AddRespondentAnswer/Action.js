"use strict";
import {ADD_RESPONDENT_ANSWER} from '../../../actions';

export default (question, choice) => dispatch => {
    dispatch({
        type: ADD_RESPONDENT_ANSWER,
        payload: {
            question: {
                id: question.id,
                cid: question.cid
            },
            choice
        }
    })
}