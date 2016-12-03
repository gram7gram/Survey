"use strict";
import {PROMOCODE_CHANGED} from '../../actions';

export default value => dispatch => {
    dispatch({
        type: PROMOCODE_CHANGED,
        payload: value
    })
}