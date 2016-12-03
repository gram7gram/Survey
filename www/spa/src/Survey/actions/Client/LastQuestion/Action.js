"use strict";
import {CLIENT_LAST_QUESTION} from '../../../actions';

export default model => dispatch => {
    dispatch({
        type: CLIENT_LAST_QUESTION,
        payload: model
    })
}