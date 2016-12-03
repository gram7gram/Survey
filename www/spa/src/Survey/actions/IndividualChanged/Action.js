"use strict";
import {INDIVIDUAL_CHANGED} from '../../actions';

export default change => {
    return {
        type: INDIVIDUAL_CHANGED,
        payload: change
    }
}