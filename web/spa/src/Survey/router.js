"use strict";
import React from 'react';
import { Router, Route, IndexRoute, hashHistory } from 'react-router';

import trans from './translator';
import Layout from './components/Layout';
import ClientIndex from './components/Pages/Client/Index';
import CompleteSurvey from './components/Pages/CompleteSurvey/Index';
import CompletedSurvey from './components/Pages/CompletedSurvey/Index';
import NoAccess from './components/NoAccess';

const toTop = () => {
    window.scrollTo(0, 0)
}

const onCompleteEnter = (getState, nextState, replace)  => {
    document.title = trans.ru.completeSurveyTitle
    toTop()

    const store = getState();

    if (store.ActiveSurvey.isDenied) {
        hashHistory.push('/no-access')
    } else if (!store.Promocode.isVerified) {
        hashHistory.push('/')
    }
}

const onCompletedEnter = (getState, nextState, replace)  => {
    document.title = trans.ru.completedSurveyTitle
    toTop()

    const store = getState();

    if (store.ActiveSurvey.isDenied) {
        hashHistory.push('/no-access')
    } else if (!store.Promocode.isVerified) {
        hashHistory.push('/')
    }
}

const onNoAccessEnter = (store, nextState, replace)  => {
    document.title = trans.ru.accessDenied
    toTop()
}

const onSurveyEnter = (store, nextState, replace)  => {
    toTop()
}

const createRouter = (store) => {
    return (
        <Router history={hashHistory}>
            <Route path="/" component={Layout}>
                <IndexRoute component={ClientIndex} onEnter={onSurveyEnter.bind(this, store)}/>
                <Route path="/complete/:id" component={CompleteSurvey} onEnter={onCompleteEnter.bind(this, store)}/>
                <Route path="/completed/:id" component={CompletedSurvey} onEnter={onCompletedEnter.bind(this, store)}/>
                <Route path="/no-access" component={NoAccess} onEnter={onNoAccessEnter.bind(this, store)}/>
            </Route>
        </Router>
    )
}

export default createRouter;