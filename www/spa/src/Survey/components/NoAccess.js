"use strict";
import trans from '../translator'
import React from 'react';

export default class Layout extends React.Component {

    render() {
        return (
            <div style={{textAlign: 'center'}}>
                <img src={SurveyResources.images.noAccess}/>

                <h3>{trans.ru.accessDenied}</h3>
                <h4>{trans.ru.accessDeniedFooter}</h4>
            </div>
        )
    }
};