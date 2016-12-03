"use strict";
import moment from 'moment';
import * as Utils from './utils'

export default (survey, options = {}) => {
    const originalSurvey = options.survey;

    const model = Utils.clone(survey)
    const user = model.user
    const individual = user.individual
    const contacts = individual.contacts
    const mobilePhone = contacts.mobilePhone
    const address = individual.address
    const city = address.city
    const answers = survey.answers

    let validator = {
        total: 0,
        count: 0,
        messages: [],
        errors: {}
    };

    if (!city || city.length < 2) {
        validator.errors.city = {
            message: 'Город должен содержать 2 и более символов'
        }
        ++validator.count
    }

    if (!individual.lastName || individual.lastName.length < 2) {
        validator.errors.lastName = {
            message: 'Фамилия должна содержать 2 и более символов'
        }
        ++validator.count
    }

    if (!individual.firstName || individual.firstName.length < 2) {
        validator.errors.firstName = {
            message: 'Имя должно содержать 2 и более символов'
        }
        ++validator.count
    }

    if (!individual.age || individual.age < 18) {
        validator.errors.age = {
            message: 'Ваш возраст должен быть 18 и более лет для участия в опросе'
        }
        ++validator.count
    }

    if (!user.email) {
        validator.errors.email = {
            message: 'Email обязателен'
        }
        ++validator.count
    } else if ((!/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(user.email))) {
        validator.errors.email = {
            message: 'Email не соответствует стандарту'
        }
        ++validator.count
    }

    if (!mobilePhone) {
        validator.errors.mobilePhone = {
            message: 'Номер телефона обязателен'
        }
        ++validator.count
    } else if ((!/^(\+?)[0-9]{12}$/.test(mobilePhone))) {
        validator.errors.mobilePhone = {
            message: 'Номер телефона не соответствует стандарту'
        }
        ++validator.count
    }

    validator.errors.answers = {}
    Utils.objectValues(originalSurvey.questions).forEach(question => {
        let questionAnswers = answers[question.cid];
        if (!questionAnswers) {
            validator.errors.answers[question.cid] = {
                message: 'Ответ на вопрос обязателен',
            }
            ++validator.count
        } else {
            const choices = Utils.objectValues(questionAnswers.choices);
            const validAnswers = choices.filter(c => c.cid && c.name);

            if (!(validAnswers.length > 0
                && choices.length > 0
                && choices.length === validAnswers.length)) {
                validator.errors.answers[question.cid] = {
                    message: 'Для заполения анкеты, все ответы обязательны'
                }
                ++validator.count
            }
        }
    });


    validator.total += validator.count;

    return validator;
}