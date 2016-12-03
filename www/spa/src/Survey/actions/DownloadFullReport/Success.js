"use strict";

import {DOWNLOAD_SURVEY_FULL_REPORT_SUCCESS} from '../../actions'
import trans from '../../translator'

export default function action(model) {
    try {
        UIToastr.init(model.humanText, trans.ru.jobCreated, 'success', {
            onclick: () => {
                window.location = SurveyRouter.GET.jobs
            }

        })
    } catch (e) {
        console.warn(e);
    }

    return {
        type: DOWNLOAD_SURVEY_FULL_REPORT_SUCCESS,
        payload: model

    }
}