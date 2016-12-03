"use strict";

import {DOWNLOAD_SURVEY_FULL_REPORT_FAILURE} from '../../actions'
import trans from '../../translator'

export default function action(params, errors) {
    try {
        UIToastr.init(trans.ru.jobNotCreated, '', 'error')
    } catch (e) {
        console.warn(e);
    }

    return {
        type: DOWNLOAD_SURVEY_FULL_REPORT_FAILURE,
        payload: {
            params,
            errors
        }
    }
}