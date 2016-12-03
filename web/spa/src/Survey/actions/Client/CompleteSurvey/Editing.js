"use strict";

import {CLIENT_SURVEY_COMPLETING} from '../../../actions'

export default (params) => {
    return {
        type: CLIENT_SURVEY_COMPLETING,
        payload: params
    }
}