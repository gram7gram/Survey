"use strict";

import {FETCHING_CLIENT_OPEN_SURVEYS} from '../../../actions'

export default function action(params) {
    return {
        type: FETCHING_CLIENT_OPEN_SURVEYS,
        payload: params
    }
}