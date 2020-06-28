import React from 'react';
import { Container } from 'react-bootstrap';
import MapController from './components/mapcontroller';
import Footer from './footer';
import Header from './header';

class ContentMapa extends React.Component {
    render() {
       return (
          <Container fluid>
             <Header/>
             {<MapController/>}
             <br/>
            <Footer/>
          </Container>
       );
    }
 }
 export default ContentMapa;