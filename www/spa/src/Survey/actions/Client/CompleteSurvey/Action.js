"use strict";
import $ from 'jquery';

import {objectValues, trimModel, clone} from '../../../utils';
import success from './Success';
import failure from './Failure';
import editing from './Editing';
import unauthorized from '../../Unauthorized/Action';

export default completedSurvey => dispatch => {

    let model = clone(completedSurvey);

    model.answers = objectValues(model.answers).map(answer => {
        answer.choices = objectValues(answer.choices).map(choice => {
            if (choice.id) {
                return {id: choice.id}
            } else {
                return {name: choice.name}
            }
        })
        answer.question = {
            id: answer.question.id
        }
        return answer;
    })
    model.survey = {
        id: model.survey.id
    }

    model = trimModel(model)

    $.ajax({
        method: 'POST',
        url: SurveyRouter.POST.completeSurvey,
        data: JSON.stringify(model),
        contentType: 'application/json',
        beforeSend: () => {
            dispatch(editing())
        },
        success: (model) => {
            dispatch(success(model))
        },
        error: (error) => {
            switch (error.status) {
                case 403:
                    dispatch(unauthorized(error.status, error.responseJSON));
                    break;
                default:
                    dispatch(failure(error.status, error.responseJSON));
            }
        }
    })

}