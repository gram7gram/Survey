import React from 'react';
import {Link, hashHistory} from 'react-router'
import {Col,Row,Button,FormControl,FormGroup} from 'react-bootstrap'
import Spinner from 'react-spinner';
import { connect } from 'react-redux';

import SDSurveyTranslator from '../../../translator'
import promocodeChanged from '../../../actions/PromocodeChanged/Action'
import fetchSurveyByPromocode from '../../../actions/FetchSurveyByPromocode/Action'

class Index extends React.Component {

    constructor() {
        super();
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

    render() {
        const isLoading = this.props.ActiveSurvey.isLoading;
        return (
            <div className="row">
                <div className="col-md-12">
                    <div className="row">
                        <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="row">
                                <div className="col-md-12 marketing-container">
                                    <h3>Грусть или подавленность?</h3>
                                </div>
                                <div className="col-md-12 image-container">
                                    <img src={SurveyResources.images.A3} className="img-responsive" alt=""
                                         style={{margin: '0 auto',display: 'block'}}/>
                                </div>
                            </div>
                        </div>
                        <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="row">
                                <div className="col-md-12 marketing-container">
                                    <h3>Привычка заедать стресс?</h3>
                                </div>
                                <div className="col-md-12 image-container">
                                    <img src={SurveyResources.images.A5} className="img-responsive"
                                         alt=""
                                         style={{margin: '0 auto',display: 'block'}}/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-12">
                            <div className=" green bar"></div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="row">
                                <div className="col-md-12 marketing-container">
                                    <h3>Не покидает чувство тревоги?</h3>
                                </div>
                                <div className="col-md-12 image-container">
                                    <img src={SurveyResources.images.A8} className="img-responsive"
                                         alt=""
                                         style={{margin: '0 auto',display: 'block'}}/>
                                </div>
                            </div>
                        </div>
                        <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="row">
                                <div className="col-md-12 marketing-container">
                                    <h3>Начальник слишком строг?</h3>
                                </div>
                                <div className="col-md-12 image-container">
                                    <img src={SurveyResources.images.A4}
                                         className="img-responsive"
                                         style={{margin: '0 auto',display: 'block'}}
                                         alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-12">
                            <div className=" green bar"></div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div className="row">
                                <div
                                    className="col-xs-12 col-sm-6 col-md-6 col-lg-6 marketing-container">
                                    <h2 style={{width: '300px', margin: '50px auto 0 auto'}}>Такие
                                        наши состояния,
                                        по своей
                                        природе,
                                        тесно связаны
                                        с дефицитом
                                        серотонина.
                                    </h2>
                                </div>
                                <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6 image-container">
                                    <img src={SurveyResources.images.A2}
                                         className="img-responsive"
                                         style={{margin: '10px auto 0 auto',display: 'block'}}
                                         alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-12">
                            <div className=" green bar"></div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div className="row">
                                <div className="col-md-12 marketing-container">
                                    <h2>Причины дефицита серотонина</h2>

                                    <div className="container-fluid" style={{maxWidth: '500px'}}>

                                        <div className=" row">
                                            <div className=" col-xs-2 col-sm-1 col-md-1 col-lg-1">
                                                <i className=" glyphicon glyphicon-heart-empty"
                                                   style={{fontSize: '24pt',marginTop: '43px'}}/>
                                            </div>
                                            <div
                                                className=" col-xs-10 col-sm-11 col-md-11 col-lg-11">
                                                <h3>несбалансированное питание
                                                    (много кофеина, мало белков,
                                                    дефицит витаминов группы В, магния)
                                                </h3>
                                            </div>
                                        </div>

                                        <div className=" row">
                                            <div className=" col-xs-2 col-sm-1 col-md-1 col-lg-1">
                                                <i className=" glyphicon glyphicon-flash"
                                                   style={{fontSize: '24pt',marginTop: '26px'}}/>
                                            </div>
                                            <div
                                                className=" col-xs-10 col-sm-11 col-md-11 col-lg-11">
                                                <h3>хронический стресс,
                                                    эмоциональная и умственная
                                                    перегрузка
                                                </h3>
                                            </div>
                                        </div>

                                        <div className=" row">
                                            <div className=" col-xs-2 col-sm-1 col-md-1 col-lg-1">
                                                <i className=" glyphicon glyphicon-home"
                                                   style={{fontSize: '24pt',marginTop: '43px'}}/>
                                            </div>
                                            <div
                                                className="col-xs-10 col-sm-11 col-md-11 col-lg-11">
                                                <h3>длительное нахождение
                                                    в закрытых помещениях
                                                    (недостаток солнечного света)
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div >

                    <div className="row">
                        <div className="col-md-12">
                            <div className=" green bar"></div>
                        </div>
                    </div>

                    < div
                        className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div className="row">
                                <div className="col-md-12" style={{textAlign:'center'}}>
                                    <h1>Нужна помощь?</h1>

                                    <h2>Используйте</h2>
                                </div>
                                <div className="col-md-12 image-container"
                                     style={{marginBottom:'15px'}}>
                                    <img src={SurveyResources.images.A6}
                                         className="img-responsive" alt=""
                                         style={{margin:'10px auto 0 auto', display: 'block', maxHeight: '70px'}}
                                        />
                                </div>

                                <div className="container-fluid" style={{maxWidth:'400px'}}>
                                    <div className="row">
                                        <div className="col-md-12" style={{marginBottom:'15px'}}>
                                            <a href="javascript:"
                                               className="btn btn-block btn-lg btn-primary"
                                               onClick={this.openSurvey}
                                               disabled={!this.props.Promocode.isValid}>

                                                {isLoading ? <Spinner/>
                                                    : <span>Получить<br/>
                                                        СМАРТСМАЙЛ (5-НТР)<br/>
                                                        БЕСПЛАТНО</span>}

                                            </a>
                                        </div>

                                        <div className="col-md-12" style={{marginBottom:'15px'}}>
                                            <a href="javascript:"
                                               className="btn btn-block btn-lg btn-info">
                                                Приобрести <br/>
                                                СМАРТСМАЙЛ (5-НТР)
                                            </a>
                                        </div>

                                        <div className="col-md-12" style={{marginBottom:'15px'}}>
                                            <a href={SurveyRouter.GET.info}
                                               className="btn btn-block btn-lg btn-default">
                                                Узнать больше о<br/>
                                                СМАРТСМАЙЛ (5-НТР)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default connect(store => {
    return {...store}
})(Index)