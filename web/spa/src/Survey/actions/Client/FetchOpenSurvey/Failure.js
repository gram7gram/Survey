"use strict";

import {FETCH_CLIENT_OPEN_SURVEYS_FAILURE} from '../../../actions'

export default function action(params, errors) {

    return {
        type: FETCH_CLIENT_OPEN_SURVEYS_FAILURE,
        payload: {
            params,
            errors
        }
    }
}