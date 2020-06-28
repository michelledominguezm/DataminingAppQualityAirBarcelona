import React from 'react';
import { Row, Col, Jumbotron, Container, Image } from 'react-bootstrap';

class Header extends React.Component {
   render() {
      return (
               <Jumbotron className ="p-2" fluid>
                  <hr className="my-2"></hr>
                  <Container fluid>
                     <Row>
                        <Col md={8}>
                           <h1>L'AIRE DE BARCELONA</h1>
                           <h6>QUALITAT DE L'AIRE DE LA CIUTAT DE BARCELONA</h6>
                           <hr className="my-1"></hr>
                           <p> 
                              Informació sobre la qualitat de l'aire de la ciutat de Barcelona a les zones on hi ha una estació de mesura.  
                              <br></br>
                              Per a la realització d'aquesta aplicació s'han emprat dades obertes provinents del Portal <a href="https://analisi.transparenciacatalunya.cat/" title="Dades obertes Catalunya" target="_blank">Dades Obertes Catalunya</a> i del Servei <a href="https://opendata-ajuntament.barcelona.cat" title="Servei Open Data BCN" target="_blank">Open Data BCN</a>, sota les llicències <a href="https://opendatacommons.org/licenses/by/1.0/" target="_blank">ODC-BY 1.0</a> i <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">CC-BY 4.0</a> respectivament.  
                           </p>
                        </Col>
                        <Col md={2}>
                           <Image src="bcn1.jpg" fluid thumbnail />   
                        </Col>
                        <Col md={2}>                   
                           <Image src="bcn2.jpg" fluid thumbnail />
                        </Col>
                     </Row>
                     
                  </Container>
                  <hr className="my-1"></hr>
               </Jumbotron>
      );
   }
}

export default Header;