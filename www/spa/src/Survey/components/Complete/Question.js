import '../../../../node_modules/react-select/dist/react-select.css';

import React from 'react';
import {Row,Col,Alert} from 'react-bootstrap';
import Select from 'react-select';

import SDSurveyTranslator from '../../translator'
import * as Utils from '../../utils';
import Choice from './Choice';
import addRespondentChoice from '../../actions/Client/AddRespondentAnswer/Action';

export default class Question extends React.Component {

    constructor() {
        super();
        this.getQuestion = this.getQuestion.bind(this)
    }

    getQuestion() {
        return this.props.question
    }

    render() {
        const question = this.getQuestion();
        if (!question) return null;

        const Choices = Utils.objectValues(question.choices).map((model, i) =>
                <Choice
                    key={i}
                    {...this.props}
                    question={question}
                    choice={model}
                    isEditable={false}/>
        );

        const answerErrors = this.props.validator.errors.answers || false;
        const errors = answerErrors ? answerErrors[question.cid] : false

        return (
            <div className="panel panel-info">
                <div className="panel-heading">
                    <div className="panel-title bold">{question.name}</div>
                </div>
                <div className="panel-body">
                    <div className="container-fluid">
                        <Row>
                            <Col md={12}>
                                {
                                    errors
                                        ? <Alert bsStyle="warning"><p>{errors.message}</p></Alert>
                                        : null
                                }
                            </Col>
                            <Col xs={12} sm={12} md={12} lg={12}>
                                {Choices}
                            </Col>
                            <Col xs={12} sm={12} md={12} lg={12}>
                                {this.renderRespondentAnswer()}
                            </Col>
                        </Row>
                    </div>
                </div>
            </div>
        );
    }

    renderRespondentAnswer() {
        const question = this.getQuestion();
        const respondentChoice = this.props.respondentChoices[question.cid]
        const canShowAnswer = question.isRespondentAnswerAllowed && respondentChoice;

        if (!canShowAnswer) return null;

        return <Choice
            {...this.props}
            question={question}
            choice={respondentChoice}
            isEditable={true}/>
    }
}