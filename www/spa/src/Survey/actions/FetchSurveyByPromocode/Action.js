"use strict";
import $ from 'jquery';

import fetching from './Fetching';
import fetchSuccess from './Success';
import fetchFailure from './Failure';
import unauthorized from '../Unauthorized/Action';

export default (promocode = false) => dispatch => {

    if (!promocode) return;

    $.ajax({
        method: 'GET',
        url: SurveyRouter.GET.surveyByPromocode.replace('__promo__', promocode),
        beforeSend: () => {
            dispatch(fetching())
        },
        success: (model) => {
            dispatch(fetchSuccess(model))
        },
        error: (error) => {
            switch (error.status) {
                case 403:
                    dispatch(unauthorized(error.status, error.responseJSON));
                    break;
                default:
                    dispatch(fetchFailure(error.status, error.responseJSON));
            }
        }
    })

}