"use strict";
import {CLIENT_NEXT_QUESTION} from '../../../actions';

export default model => dispatch => {
    dispatch({
        type: CLIENT_NEXT_QUESTION,
        payload: model
    })
}