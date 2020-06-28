import React from 'react';
import { Container } from 'react-bootstrap';
import PeriodController from './components/periodcontroller';
import Footer from './footer';
import Header from './header';

class ContentGrafics extends React.Component {
    render() {
       return (
          <Container fluid>
             <Header/>
             {<PeriodController/>}
             <br/>
            <Footer/>
          </Container>
       );
    }
 }
 export default ContentGrafics;