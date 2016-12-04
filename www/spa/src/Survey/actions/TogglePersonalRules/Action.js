"use strict";
import {TOGGLE_PERSONAL_RULES} from '../../actions';

export default state => {
    return {
        type: TOGGLE_PERSONAL_RULES,
        payload: state
    }
}