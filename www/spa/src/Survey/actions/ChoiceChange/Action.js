"use strict";
import {QUESTION_CHOICE_CHANGED} from '../../actions';

export default (question, choice, change) => dispatch => {
    dispatch({
        type: QUESTION_CHOICE_CHANGED,
        payload: {
            question: {
                id: question.id,
                cid: question.cid
            },
            choice: {
                id: choice.id,
                cid: choice.cid
            },
            change
        }
    })
}