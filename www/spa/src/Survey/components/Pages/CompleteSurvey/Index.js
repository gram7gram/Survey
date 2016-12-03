import React from 'react';
import { hashHistory } from 'react-router';
import { connect } from 'react-redux';

import Survey from '../../Complete/Survey'

class Index extends React.Component {

    componentWillReceiveProps(nextProps) {
        if (nextProps.ActiveSurvey.isSaved) {
            let id = parseInt(nextProps.ActiveSurvey.completedSurvey.survey.id);
            if (!isNaN(id) && id > 0) {
                hashHistory.push('/completed/' + id)
            } else {
                hashHistory.push('/no-access')
            }
        }
    }

    render() {

        return (
            <Survey
                {...this.props}
                />
        )
    }
}

export default connect(store => {
    return store
})(Index)