"use strict";

import {FETCHING_SURVEY} from '../../actions'

export default function action(params) {
    return {
        type: FETCHING_SURVEY,
        payload: params
    }
}