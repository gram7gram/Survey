import React from 'react';
import {Link, hashHistory} from 'react-router'
import {Col,Row,Button,FormControl,FormGroup} from 'react-bootstrap'
import Spinner from 'react-spinner';
import { connect } from 'react-redux';

import trans from '../../../translator'
import promocodeChanged from '../../../actions/PromocodeChanged/Action'
import fetchSurveyByPromocode from '../../../actions/FetchSurveyByPromocode/Action'
import toggleRulesAction from '../../../actions/ToggleRules/Action'
import toggleAcceptRules from '../../../actions/ToggleAcceptRules/Action'

class Index extends React.Component {

    constructor() {
        super();
        this.acceptRules = this.acceptRules.bind(this)
        this.toggleRules = this.toggleRules.bind(this)
        this.setPromocode = this.setPromocode.bind(this)
        this.openSurvey = this.openSurvey.bind(this)
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.ActiveSurvey.isFound) {
            let id = nextProps.ActiveSurvey.survey.id;
            hashHistory.push('/complete/' + id)
        }
    }

    setPromocode(e) {
        this.props.dispatch(promocodeChanged(e.target.value))
    }

    openSurvey() {
        if (!this.props.Promocode.isValid) return;

        this.props.dispatch(fetchSurveyByPromocode(this.props.Promocode.value))
    }

    toggleRules() {
        this.props.dispatch(toggleRulesAction(!this.props.ActiveSurvey.canShowRules))
    }

    acceptRules(e) {
        this.props.dispatch(toggleAcceptRules(e.target.checked))
    }

    render() {
        const isLoading = this.props.ActiveSurvey.isLoading;
        const areRulesAccepted = this.props.ActiveSurvey.areRulesAccepted;
        const canStart = this.props.Promocode.isValid && areRulesAccepted
        return (
            <div className="row">
                <div className="col-md-12">
                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <h1 className="bold">СмартСмайл</h1>

                            <h2 className="bold">помогает победить стресс</h2>

                            <h5>(ознакомительная программа)</h5>

                            <h4>Если Вы ищете для себя или своих близких</h4>
                            <h4>эффективное средство на натуральной основе</h4>
                            <h4>с целью повышения стрессоустойчивости,
                                снижения тревожности, снятия состояния угнетенности,
                                устранения последствий стресса
                            </h4>

                            <h3 className="bold">Вы можете воспользоваться представленным здесь предложением</h3>

                            <table className="rules">
                                <tbody>
                                <tr>
                                    <td><h4 className="font-40">1</h4></td>
                                    <td><h4>Ознакомиться с текстом Инструкции для добавки диетической «СмартСмайл»</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h4 className="font-40">2</h4></td>
                                    <td><h4>Ознакомиться с&nbsp;
                                        <a href="javascript:"
                                           className="black-link"
                                           onClick={this.toggleRules}>Правилами ознакомительной
                                            Программы</a>
                                    </h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h4 className="font-40">3</h4></td>
                                    <td><h4>Оформить электронную заявку на получение
                                        ознакомительного образца</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h4 className="font-40">4</h4></td>
                                    <td><h4>Получить подтверждение регистрации</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h4 className="font-40">5</h4></td>
                                    <td><h4>Получить 1 ознакомительный образец добавки диетической
                                        «СмартСмайл 20 капсул по 100 мг»</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <img src={SurveyResources.images.A7}
                                 className="img-responsive"
                                 alt="SmartSmile упаковка"
                                 style={{margin: '0 auto',maxHeight: '300px'}}/>

                        </div>
                    </div>

                    <Row>

                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"
                             style={{maginTop: '30px'}}>
                            <input
                                type="checkbox"
                                value={areRulesAccepted ? "on" : "off"}
                                checked={areRulesAccepted}
                                onChange={this.acceptRules}
                                />
                            Настоящим подтверждаю, что я ознакомился с&nbsp;
                            <a href="javascript:" onClick={this.toggleRules}>Правилами ознакомительной Программы</a>.
                        </div>
                    </Row>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            {this.renderRules()}
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-12 text-center">

                            {
                                this.props.ActiveSurvey.isNotFound
                                    ? <h4 style={{color:'red'}}>Анкета не найдена</h4>
                                    : <a href="javascript:"
                                         className="btn btn-block btn-lg btn-participate btn-primary"
                                         onClick={this.openSurvey}
                                         disabled={!canStart}>

                                    {isLoading ? <Spinner/>
                                        : <span>Принять участие</span>}

                                </a>
                            }

                        </div>
                    </div>

                </div>
            </div>
        )
    }


    renderRules() {
        if (!this.props.ActiveSurvey.canShowRules) {
            return null
        }

        return <div>
            <div className="rules">
                <small>
                    <p>{trans.ru.surveyRuleHeader}</p>
                    <ul>
                        {trans.ru.surveyRuleCondotions.map((condition, i) =>
                                <li key={i}>{condition}</li>
                        )}
                    </ul>
                </small>
            </div>
            <div className="rules">
                <p><strong>Внимание!
                    В случае, если ознакомительный образец будет Вам полезен, мы готовы
                    предоставить Вам ещё 1 (одну) полную ознакомительную упаковку «СмартСмайл
                    20 капсул по 100 мг». Условие для ее получения – отправка Вами краткого отзыва
                    на электронную почту&nbsp;
                    <a className="black-link"
                       href="mailto:omnifarma-kiev@mail.ua">omnifarma-kiev@mail.ua</a>.</strong>
                </p>
            </div>
        </div>
    }
}

export default connect(store => {
    return {...store}
})(Index)