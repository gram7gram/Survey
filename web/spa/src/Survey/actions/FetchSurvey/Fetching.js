"use strict";

import {FETCHING_SURVEYS} from '../../actions'

export default function action(params) {
    return {
        type: FETCHING_SURVEYS,
        payload: params
    }
}