"use strict";
import {CITY_CHANGED} from '../../actions';

export default change => {
    return {
        type: CITY_CHANGED,
        payload: change
    }
}