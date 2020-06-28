import React from 'react';
import { Container } from 'react-bootstrap';
import MapController from './components/mapcontroller';
import PeriodController from './components/periodcontroller';
import Footer from './footer';
import Header from './header';

class Content extends React.Component {
    render() {
       return (
          <Container fluid>
             <Header/>
             {<MapController/>}
             {<PeriodController/>}
             <br/>
            <Footer/>
          </Container>
       );
    }
 }
 export default Content;