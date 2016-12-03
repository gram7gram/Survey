"use strict";

import {FETCH_SURVEY_FAILURE} from '../../actions'

export default function action(params, errors) {

    return {
        type: FETCH_SURVEY_FAILURE,
        payload: {
            params,
            errors
        }
    }
}