"use strict";
import {TOGGLE_ACCEPT_RULES} from '../../actions';

export default state => {
    return {
        type: TOGGLE_ACCEPT_RULES,
        payload: state
    }
}