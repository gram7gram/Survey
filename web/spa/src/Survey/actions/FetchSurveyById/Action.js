"use strict";
import $ from 'jquery';

import fetchSuccess from './Success';
import fetchFailure from './Failure';
import unauthorized from '../Unauthorized/Action';

export default id => dispatch => {

    $.ajax({
        method: 'GET',
        url: SurveyRouter.GET.survey.replace('__id__', id),
        success: (model) => {
            dispatch(fetchSuccess(model))
        },
        error: (error) => {
            switch (error.status) {
                case 403:
                    dispatch(unauthorized(id, error.responseJSON));
                    break;
                default:
                    dispatch(fetchFailure(id, error.responseJSON));
            }
        }
    })

}