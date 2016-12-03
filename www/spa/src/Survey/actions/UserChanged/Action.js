"use strict";
import {USER_CHANGED} from '../../actions';

export default change => {
    return {
        type: USER_CHANGED,
        payload: change
    }
}