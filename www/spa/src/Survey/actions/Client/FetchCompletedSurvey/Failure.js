"use strict";

import {FETCH_CLIENT_COMPLETED_SURVEYS} from '../../../actions'

export default function action(params, errors) {

    return {
        type: FETCH_CLIENT_COMPLETED_SURVEYS,
        payload: {
            params,
            errors
        }
    }
}