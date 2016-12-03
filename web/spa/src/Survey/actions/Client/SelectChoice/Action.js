"use strict";
import {CLIENT_CHOICE_CHANGED} from '../../../actions';

export default (question, choices) => dispatch => {
    dispatch({
        type: CLIENT_CHOICE_CHANGED,
        payload: {
            question,
            choices
        }
    })
}