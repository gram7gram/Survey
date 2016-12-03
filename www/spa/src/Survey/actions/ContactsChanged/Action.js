"use strict";
import {CONTACTS_CHANGED} from '../../actions';

export default change => {
    return {
        type: CONTACTS_CHANGED,
        payload: change
    }
}