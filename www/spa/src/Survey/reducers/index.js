"use strict";
import { combineReducers } from 'redux';

import ActiveSurvey from './activeSurvey/Survey';
import Promocode from './Promocode';

export default combineReducers({
    Promocode,
    ActiveSurvey
});