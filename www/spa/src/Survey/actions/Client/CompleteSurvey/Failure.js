"use strict";

import {CLIENT_SURVEY_COMPLETE_FAILURE} from '../../../actions'

export default (params, response) => {

    return {
        type: CLIENT_SURVEY_COMPLETE_FAILURE,
        payload: {
            params,
            ...response
        }
    }
}