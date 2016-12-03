"use strict";

import {UNAUTHORIZED_ERROR} from '../../actions'

export default (id, error) => {
    return {
        type: UNAUTHORIZED_ERROR,
        payload: {
            id, error
        }
    }
}