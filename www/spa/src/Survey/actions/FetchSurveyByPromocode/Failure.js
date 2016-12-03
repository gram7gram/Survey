"use strict";

import {FETCH_SURVEY_BY_PROMOCODE_FAILURE} from '../../actions'

export default function action(params, errors) {

    return {
        type: FETCH_SURVEY_BY_PROMOCODE_FAILURE,
        payload: {
            params,
            errors
        }
    }
}