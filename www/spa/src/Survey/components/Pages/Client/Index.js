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
            <Row>
                <Col xs={12} sm={12} md={12} lg={12}>
                    <FormGroup>
                        <FormControl
                            style={{marginTop: '100px',maxWidth:'300px'}}
                            type="text"
                            value={this.props.Promocode.value || ''}
                            readOnly={isLoading}
                            onChange={this.setPromocode}/>
                    </FormGroup>

                    {
                        isLoading ? <Spinner/>
                            : <Button
                            bsSize="small"
                            bsStyle="primary"
                            onClick={this.openSurvey}
                            disabled={!this.props.Promocode.isValid}>START</Button>
                    }

                </Col>
            </Row>
        )
    }
}

export default connect(store => {
    return {...store}
})(Index)