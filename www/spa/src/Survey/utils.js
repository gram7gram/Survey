"use strict";
import keyBy from 'lodash/keyBy';

export function isSameModels(model1 = {}, model2 = {}) {
    return (!!model1.cid && !!model2.cid && model1.cid === model2.cid)//client created range
        || (!!model1.id && !!model2.id && model1.id === model2.id);//server created range
}

export function cid(length = 5) {
    return Math.random().toString(36).replace(/[^a-z0-9]+/g, '').substr(0, length);
}

export function symmetricDiff(a1, a2) {
    var result = [];
    for (var i = 0; i < a1.length; i++) {
        if (a2.indexOf(a1[i]) === -1) {
            result.push(a1[i]);
        }
    }
    for (i = 0; i < a2.length; i++) {
        if (a1.indexOf(a2[i]) === -1) {
            result.push(a2[i]);
        }
    }
    return result;
}

export function objectValues(obj) {
    if (!obj) return [];

    return Object.keys(obj).map(function (key) {
        return obj[key];
    });
}

export function trimModel(model, blacklist = ['cid', 'toString']) {

    let modelClone = Object.assign({}, model);
    let newModel = {};

    for (let property in modelClone) {
        if (!modelClone.hasOwnProperty(property)) continue;
        let value = modelClone[property];
        if (null === value) {
            delete modelClone[property];
            continue;
        }

        if (blacklist.indexOf(property) !== -1) {
            delete modelClone[property];
            value = modelClone[property]
        } else if (Array.isArray(value)) {
            value = value.map((m) => trimModel(m))
        } else if (value && typeof value === 'object') {
            value = trimModel(value)
        }

        newModel[property] = value
    }

    return newModel;
}

export function parseSurveyOnSuccess(survey) {

    survey.questions = keyBy(survey.questions.map(question => {
        question.cid = cid()
        question.choices = keyBy(question.choices.map(choice => {
            choice.cid = cid()
            return choice;
        }), 'cid')
        return question;
    }), 'cid');

    return survey;
}

export function clone(obj) {
    return JSON.parse(JSON.stringify(obj))
}