import React from 'react';
import {Row,Col,FormGroup,FormControl,Button} from 'react-bootstrap';

import trans from '../../translator'
import {cid} from '../../utils'
import selectChoice from '../../actions/Client/SelectChoice/Action'
import clientChoiceChanged from '../../actions/Client/ChoiceChange/Action'

export default class Choice extends React.Component {

    constructor() {
        super();
        this.getChoice = this.getChoice.bind(this)
        this.addAnswer = this.addAnswer.bind(this)
        this.isMultiple = this.isMultiple.bind(this)
        this.getQuestionsAnswers = this.getQuestionsAnswers.bind(this)
        this.setName = this.setName.bind(this)
    }

    getChoice() {
        return this.props.choice
    }

    getQuestion() {
        return this.props.question
    }

    getQuestionsAnswers() {
        return this.props.model.answers[this.getQuestion().cid] || {};

    }

    addAnswer(e) {
        const choice = this.getChoice();
        const question = this.getQuestion();
        const answers = Object.assign({}, this.getQuestionsAnswers());
        let choices = answers.choices || {};

        if (!this.isMultiple()) {
            choices = {}
        }

        if (e.target.checked) {
            choices[choice.cid] = choice;
        } else {
            delete choices[choice.cid]
        }

        this.props.dispatch(selectChoice(question, choices))
    }

    isMultiple() {
        return this.getQuestion().type.key === 'multiple'
    }

    setName(e) {
        this.props.dispatch(clientChoiceChanged(this.getQuestion(), this.getChoice(), {
            name: e.target.value
        }))
    }

    renderName() {
        const choice = this.getChoice();
        if (this.props.isEditable) {
            return <FormControl
                type="text"
                value={choice.name || ''}
                onChange={this.setName}
                placeholder={trans.ru.other}/>
        }
        return <h4>{choice.name}</h4>
    }

    render() {
        const choice = this.getChoice();
        const question = this.getQuestion();
        const isMultiple = this.isMultiple();
        const name = 'question-' + question.cid + '-choice';
        const answers = this.getQuestionsAnswers().choices || {};

        return (
            <div className="row answer-choice">
                <Col xs={1} sm={1} md={1} lg={1}>
                    {
                        isMultiple
                            ? <input
                            type={'checkbox'}
                            name={name}
                            checked={!!answers[choice.cid]}
                            onChange={this.addAnswer}
                            value={choice.cid}/>
                            : <input
                            type={'radio'}
                            name={name}
                            selected={!!answers[choice.cid]}
                            onChange={this.addAnswer}
                            value={choice.cid}/>
                    }
                </Col>
                <Col xs={11} sm={11} md={11} lg={11}>
                    {this.renderName()}
                </Col>
            </div>
        )
    }
}