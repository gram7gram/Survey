"use strict";
import keyBy from 'lodash/keyBy'

import {FETCH_SURVEYS_SUCCESS} from '../../actions'
import {parseSurveyOnSuccess} from '../../utils';

export default function action(collection) {
    let items = collection.items.map(survey => parseSurveyOnSuccess(survey));
    let pagination = Object.assign({}, collection);
    delete pagination.items;

    return {
        type: FETCH_SURVEYS_SUCCESS,
        payload: {
            pagination,
            items: keyBy(items, 'id')
        }
    }
}