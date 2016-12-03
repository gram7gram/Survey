"use strict";
import $ from 'jquery';

import fetchSuccess from './Success';
import fetchFailure from './Failure';
import fetching from './Fetching';
import unauthorized from '../../Unauthorized/Action';

export default (data = {
    page: 1,
    limit: 10,
    filter: []
}) => dispatch => {

    let {
        page = 1,
        limit = 10,
        filter = []
        } = data;
    $.ajax({
        method: 'GET',
        url: SurveyRouter.GET.clientSurveys,
        data: {
            page, limit, filter
        },
        beforeSend: () => {
            dispatch(fetching(data))
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