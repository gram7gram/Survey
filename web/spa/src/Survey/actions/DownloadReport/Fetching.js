"use strict";

import {DOWNLOADING_SURVEY_REPORT} from '../../actions'

export default function action(params) {
    return {
        type: DOWNLOADING_SURVEY_REPORT,
        payload: params
    }
}