"use strict";
import keyBy from 'lodash/keyBy'

import {FETCH_CLIENT_OPEN_SURVEYS_SUCCESS} from '../../../actions'

export default function action(collection) {
    let items = collection.items;
    let pagination = Object.assign({}, collection);
    delete pagination.items;

    return {
        type: FETCH_CLIENT_OPEN_SURVEYS_SUCCESS,
        payload: {
            pagination,
            items: keyBy(items, 'id')
        }
    }
}