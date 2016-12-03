"use strict";

import {FETCH_SURVEYS_FAILURE} from '../../actions'

export default function action(params, errors) {

    return {
        type: FETCH_SURVEYS_FAILURE,
        payload: {
            params,
            errors
        }
    }
}