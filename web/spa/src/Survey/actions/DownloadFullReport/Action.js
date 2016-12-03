"use strict";
import $ from 'jquery';

import fetchSuccess from './Success';
import fetchFailure from './Failure';
import fetching from './Fetching';
import unauthorized from '../Unauthorized/Action';

export default id => dispatch => {

    $.ajax({
        method: 'POST',
        url: SurveyRouter.POST.fullReportJob.replace('__id__', id),
        beforeSend: () => {
            dispatch(fetching())
        },
        success: (collection) => {
            dispatch(fetchSuccess(collection))
        },
        error: (error) => {
            switch (error.status) {
                case 403:
                    dispatch(unauthorized(null, error.responseJSON));
                    break;
                default:
                    dispatch(fetchFailure(null, error.responseJSON));
            }
        }
    })

}