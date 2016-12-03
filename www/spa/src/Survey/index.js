"use strict";

import '../../node_modules/bootstrap.native/dist/bootstrap-native.min.js';
import './style/index.css';

import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';

import store from './store'
import Routing from './router'

const id = 'survey-app';
const app = document.getElementById(id);
if (!app) {
    throw 'No DOM element with id: ' + id
}

render(
    <Provider store={store}>
        {Routing(store.getState)}
    </Provider>,
    app
);