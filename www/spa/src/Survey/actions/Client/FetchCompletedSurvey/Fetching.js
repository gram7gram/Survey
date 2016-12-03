"use strict";

import {FETCHING_CLIENT_COMPLETED_SURVEYS} from '../../../actions'

export default function action(params) {
    return {
        type: FETCHING_CLIENT_COMPLETED_SURVEYS,
        payload: params
    }
}