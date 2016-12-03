"use strict";

import {createStore, applyMiddleware} from 'redux';
import {hashHistory} from 'react-router';

import logger from 'redux-logger';
import thunk from 'redux-thunk';
import promise from 'redux-promise-middleware';

import reducers from './reducers';

let middleware;

if (process.env.NODE_ENV === 'production') {
    middleware = applyMiddleware(promise(), thunk)
} else {
    middleware = applyMiddleware(promise(), thunk, logger())
}

const store = createStore(reducers, middleware);

export default store;