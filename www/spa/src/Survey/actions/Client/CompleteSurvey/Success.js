"use strict";

import {CLIENT_SURVEY_COMPLETE_SUCCESS} from '../../../actions'

export default (model) => {

    return {
        type: CLIENT_SURVEY_COMPLETE_SUCCESS,
        payload: model
    }
}