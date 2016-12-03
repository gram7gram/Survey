"use strict";
import trans from '../../../translator'
import React from 'react';
import {connect} from 'react-redux';
import {Link} from 'react-router';

class Index extends React.Component {

    render() {
        const getFullname = (user) => {
            if (!user.individual) return trans.ru.client
            return [user.individual.lastName, user.individual.firstName].join(' ').trim()
        }
        const user = this.props.model.user;
        return (
            <div style={{textAlign: 'center'}}>
                <img src={SurveyResources.images.noSurveys}/>

                <h3>{trans.ru.clientCompletedSurvey.replace('%FULLNAME%', getFullname(user))}</h3>
                <h4 dangerouslySetInnerHTML={{__html: trans.ru.clientCompletedSurveyFooter.replace('%FULLNAME%', getFullname(user))}}/>
            </div>
        )
    }
}

export default connect(store => {
    return store.ActiveSurvey
})(Index)