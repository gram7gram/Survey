"use strict";
import {CLIENT_SURVEY_STARTING} from '../../../actions';

export default survey => dispatch => {
    dispatch({
        type: CLIENT_SURVEY_STARTING,
        payload: survey
    })
}