"use strict";
import {TOGGLE_RULES} from '../../actions';

export default state => {
    return {
        type: TOGGLE_RULES,
        payload: state
    }
}