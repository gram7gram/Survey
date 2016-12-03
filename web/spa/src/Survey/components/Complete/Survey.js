"use strict";
import '../../../../node_modules/react-intl-tel-input/dist/main.css';
import '../../../../node_modules/react-intl-tel-input/dist/libphonenumber.js';

import React from 'react';
import {Row,Col,FormGroup,FormControl,Button,ButtonGroup,Portlet,HelpBlock,Alert} from 'react-bootstrap';
import Spinner from 'react-spinner'
import PhoneInput from 'react-intl-tel-input';

import trans from '../../translator'
import * as Utils from '../../utils';
import Question from './Question';
import nextQuestion from '../../actions/Client/NextQuestion/Action'
import lastQuestion from '../../actions/Client/LastQuestion/Action'
import answerQuestion from '../../actions/Client/AnswerQuestion/Action'
import completeSurvey from '../../actions/Client/CompleteSurvey/Action'
import toggleRulesAction from '../../actions/ToggleRules/Action'
import toggleAcceptRules from '../../actions/ToggleAcceptRules/Action'
import individualChanged from '../../actions/IndividualChanged/Action'
import userChanged from '../../actions/UserChanged/Action'
import contactsChanged from '../../actions/ContactsChanged/Action'
import cityChanged from '../../actions/CityChanged/Action'
import validate from '../../actions/Validate/Action'

export default class Survey extends React.Component {

    constructor() {
        super();
        this.acceptRules = this.acceptRules.bind(this)
        this.toggleRules = this.toggleRules.bind(this)
        this.getSurvey = this.getSurvey.bind(this)
        this.getActiveSurvey = this.getActiveSurvey.bind(this)
        this.submitSurvey = this.submitSurvey.bind(this)
        this.isLastQuestion = this.isLastQuestion.bind(this)
        this.setAge = this.setAge.bind(this)
        this.setCity = this.setCity.bind(this)
        this.setFirstName = this.setFirstName.bind(this)
        this.setLastName = this.setLastName.bind(this)
        this.setMobilePhone = this.setMobilePhone.bind(this)
        this.setEmail = this.setEmail.bind(this)
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.ActiveSurvey.canValidate) {
            this.props.dispatch(validate(nextProps.ActiveSurvey.model, {
                survey: nextProps.ActiveSurvey.survey
            }));
        }
    }

    getSurvey() {
        return this.props.ActiveSurvey.survey;
    }

    getActiveSurvey() {
        return this.props.ActiveSurvey.model;
    }

    isLastQuestion() {
        const orders = this.props.ActiveSurvey.questionOrder;
        const question = this.getCurrentQuestion()
        return question && question.cid === orders[orders.length - 1]
    }

    isFirstQuestion() {
        return this.props.ActiveSurvey.currentQuestionIndex === 0
    }

    submitSurvey() {
        this.props.dispatch(completeSurvey(this.getActiveSurvey()));
    }

    getCurrentQuestion() {
        const orders = this.props.ActiveSurvey.questionOrder;
        const cid = orders[this.props.ActiveSurvey.currentQuestionIndex]
        return this.props.ActiveSurvey.questions[cid];
    }

    toggleRules() {
        this.props.dispatch(toggleRulesAction(!this.props.ActiveSurvey.canShowRules))
    }

    acceptRules(e) {
        this.props.dispatch(toggleAcceptRules(e.target.checked))
    }

    setFirstName(e) {
        this.props.dispatch(individualChanged({
            firstName: e.target.value
        }))
    }

    setLastName(e) {
        this.props.dispatch(individualChanged({
            lastName: e.target.value
        }))
    }

    setAge(e) {
        this.props.dispatch(individualChanged({
            age: parseInt(e.target.value)
        }))
    }

    setMobilePhone(isValid, shortNumber, event, fullNumber) {
        if (!isValid) return;
        fullNumber = fullNumber.replace(/[^0-9]/g, '');
        this.props.dispatch(contactsChanged({
            number: fullNumber
        }))
    }

    setEmail(e) {
        this.props.dispatch(userChanged({
            email: e.target.value
        }))
    }

    setCity(e) {
        this.props.dispatch(cityChanged({
            name: e.target.value
        }))
    }

    renderContent() {
        const survey = this.getSurvey();

        return Utils.objectValues(survey.questions)
            .sort((a, b) => {
                if (a.order < b.order) return -1
                if (a.order > b.order) return 1
                return 0
            })
            .map(question =>
                <Question
                    key={question.id}
                    {...this.props.ActiveSurvey}
                    dispatch={this.props.dispatch}
                    question={question}
                    survey={survey}/>
        )
    }

    renderGlobalErrors() {
        const globalErrors = this.props.ActiveSurvey.globalErrors
        if (globalErrors.length === 0) return null;

        return <Alert bsStyle="danger">
            {globalErrors.map((e, i) => <p key={i}>{e}</p>)}
        </Alert>
    }

    render() {

        if (this.props.ActiveSurvey.isLoading) {
            return <Spinner/>
        }

        const survey = this.getSurvey();
        const user = this.props.ActiveSurvey.model.user;
        const phone = user.individual.contacts.mobilePhone;
        const email = user.email;
        const address = user.individual.address;
        const areRulesAccepted = this.props.ActiveSurvey.areRulesAccepted;
        const validator = this.props.ActiveSurvey.validator;
        const isValid = validator.total === 0;

        return (
            <div>
                <Row>
                    <Col md={12}>
                        <h2>{survey.name}</h2>

                        <p>{survey.description}</p>
                    </Col>
                    <Col md={12}>
                        {this.renderGlobalErrors()}
                        {this.props.ActiveSurvey.isSaving ? <Spinner/> : null}
                    </Col>
                </Row>
                <Row>

                    <Col md={12}>
                        <input
                            type="checkbox"
                            value={areRulesAccepted ? "on" : "off"}
                            checked={areRulesAccepted}
                            onChange={this.acceptRules}
                            />
                        Настоящим подтверждаю, что я ознакомился с&nbsp;
                        <a href="javascript:" onClick={this.toggleRules}>Правилами ознакомительной Программы</a>.
                    </Col>
                </Row>
                <Row>
                    <Col md={12}>
                        {this.renderRules()}
                    </Col>
                </Row>
                <Row>
                    <Col md={12}>
                        <Row>
                            <Col xs={12} sm={12} md={12} lg={12}>
                                <h4>Прошу внести меня в перечень участников Программы и предоставить ознакомительный
                                    образец:</h4>
                            </Col>
                        </Row>
                        <Row>
                            <Col md={12}>
                                <div className="panel panel-info">
                                    <div className="panel-heading">
                                        <div className="panel-title">Персональная информация</div>
                                    </div>
                                    <div className="panel-body">
                                        <div className="container-fluid">
                                            <Row>
                                                <Col xs={12} sm={6} md={6} lg={3}>
                                                    <FormGroup
                                                        validationState={validator.errors.firstName ? 'error' : null}>
                                                        <label htmlFor="">Имя</label>
                                                        <FormControl
                                                            type="text"
                                                            value={user.individual.firstName || ''}
                                                            onChange={this.setFirstName}/>
                                                        {validator.errors.firstName
                                                            ?
                                                            <HelpBlock>{validator.errors.firstName.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                                <Col xs={12} sm={6} md={6} lg={3}>
                                                    <FormGroup
                                                        validationState={validator.errors.lastName ? 'error' : null}>
                                                        <label htmlFor="">Фамилия</label>
                                                        <FormControl
                                                            type="text"
                                                            value={user.individual.lastName || ''}
                                                            onChange={this.setLastName}/>
                                                        {validator.errors.lastName
                                                            ? <HelpBlock>{validator.errors.lastName.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                            </Row>
                                            <Row>
                                                <Col xs={12} sm={6} md={6} lg={3}>
                                                    <FormGroup validationState={validator.errors.age ? 'error' : null}>
                                                        <label htmlFor="">Возраст, полных лет</label>
                                                        <FormControl
                                                            type="text"
                                                            value={user.individual.age || ''}
                                                            onChange={this.setAge}/>
                                                        {validator.errors.age
                                                            ? <HelpBlock>{validator.errors.age.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                                <Col xs={12} sm={6} md={6} lg={3}>
                                                    <FormGroup validationState={validator.errors.city ? 'error' : null}>
                                                        <label htmlFor="">Город проживания:</label>
                                                        <FormControl
                                                            type="text"
                                                            value={address && address.city ? address.city : ''}
                                                            onChange={this.setCity}/>
                                                        {validator.errors.city
                                                            ? <HelpBlock>{validator.errors.city.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                            </Row>
                                            <Row>
                                                <Col xs={12} sm={12} md={6} lg={6}>
                                                    <FormGroup
                                                        validationState={validator.errors.mobilePhone ? 'error' : null}>
                                                        <label htmlFor="">Контактный мобильный телефон</label>

                                                        <PhoneInput
                                                            defaultCountry="ua"
                                                            preferredCountries={['ua']}
                                                            utilsScript={'libphonenumber.js'}
                                                            onPhoneNumberChange={this.setMobilePhone}
                                                            css={['intl-tel-input', 'form-control']}
                                                            value={phone || ''}
                                                            />
                                                        {validator.errors.mobilePhone
                                                            ?
                                                            <HelpBlock>{validator.errors.mobilePhone.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                                <Col xs={12} sm={12} md={6} lg={6}>
                                                    <FormGroup
                                                        validationState={validator.errors.email ? 'error' : null}>
                                                        <label htmlFor="">Контактный e-mail</label>
                                                        <FormControl
                                                            type="text"
                                                            value={email || ''}
                                                            onChange={this.setEmail}/>
                                                        {validator.errors.email
                                                            ? <HelpBlock>{validator.errors.email.message}</HelpBlock>
                                                            : null}
                                                    </FormGroup>
                                                </Col>
                                            </Row>
                                        </div>
                                    </div>
                                </div>
                            </Col>
                        </Row>
                    </Col>
                    <Col md={12}>
                        {this.renderContent()}
                    </Col>

                    <Col md={12} style={{textAlign: 'right'}}>
                        <FormGroup>
                            {
                                this.props.ActiveSurvey.isSaving
                                    ? <Spinner/>
                                    : <Button
                                    bsStyle="primary"
                                    disabled={!(areRulesAccepted && isValid)}
                                    onClick={this.submitSurvey}>{trans.ru.submitCompletedSurvey}</Button>
                            }
                        </FormGroup>
                    </Col>
                </Row>
            </div>
        );
    }

    renderRules() {
        if (!this.props.ActiveSurvey.canShowRules) {
            return null
        }

        return <div className="rules">
            <small>
                <p>{trans.ru.surveyRuleHeader}</p>
                <ul>
                    {trans.ru.surveyRuleCondotions.map((condition, i) =>
                            <li key={i}>{condition}</li>
                    )}
                </ul>
            </small>
        </div>
    }

}
