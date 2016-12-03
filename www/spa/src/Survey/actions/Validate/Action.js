"use strict";
import * as Action from '../../actions';
import validator from '../../validator'

export default (model, options = {}) => dispatch => {
    const result = validator(model, options);
    if (result.total > 0) {
        dispatch({
            type: Action.SURVEY_VALIDATION_FAILED,
            payload: result
        })
    } else {
        dispatch({
            type: Action.SURVEY_VALIDATION_SUCCESS
        })
    }
}