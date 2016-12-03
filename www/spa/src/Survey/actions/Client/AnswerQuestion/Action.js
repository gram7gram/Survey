"use strict";
import {CLIENT_QUESTION_ANSWERED} from '../../../actions';

export default (question, answers) => dispatch => {
    dispatch({
        type: CLIENT_QUESTION_ANSWERED,
        payload: {
            question: {
                id: question.id,
                cid: question.cid
            },
            answers
        }
    })
}