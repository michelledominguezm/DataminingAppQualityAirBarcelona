import React from 'react';
import { Row, Col, Form, FormGroup, Button, ButtonGroup } from 'react-bootstrap';

const FormController = (props) => {
    let contaminants = props.contaminants;
    let indicadors = props.indicadors;
    
    let contaminant = props.contaminant;
    let indicador = props.indicador;
    let dia = props.dia;
    let mes = props.mes;
    let any = props.any;
    
    let handleChange = props.handleChange;
    let handleSubmit = props.handleSubmit;

    let opcions_contaminants = contaminants.map((contaminant) =>
      <option 
        key={contaminant.id}
        value={contaminant.id}
      >
        {contaminant.simbol}
      </option>
   );

    let opcions_indicadors = indicadors.map((indicador) =>
      <option 
        key={indicador.id}
        value={indicador.id}
      >
        {indicador.nom}
      </option>
    );

    let dies = new Date(any, mes, 0).getDate(); 
    let opcions_dies = [];
    for (let i = 0; i < dies; i++) {
      opcions_dies.push(i+1);
    }
    opcions_dies = opcions_dies.map((opcio) =>
      <option 
        key={opcio}
        value={opcio}
      >
        {opcio}
      </option>
   );

    let opcions_mesos = [];
    let mesos = 12;
    if(new Date().getFullYear()==any){
      mesos = new Date().getMonth() + 1;
    }
    for (let i = 1; i <= mesos; i++) {
      opcions_mesos.push(i);
    }
    opcions_mesos = opcions_mesos.map((opcio) =>
      <option 
        key={opcio}
        value={opcio}
      >
        {opcio}
      </option>
   );

    
    return(
      <Row>
        <Col md={10}>
          <Form>
            <Form.Row>
              <Form.Group as={Col} controlId="select.contaminant">
                <Form.Label>Contaminant</Form.Label>
                <Form.Control size="sm" as="select" onChange={handleChange} value={contaminant}>
                  {opcions_contaminants}
                </Form.Control>
              </Form.Group>

              <Form.Group as={Col} controlId="select.indicador">
                <Form.Label>Indicador</Form.Label>
                <Form.Control size="sm" as="select" onChange={handleChange} value={indicador}>
                  {opcions_indicadors}
                </Form.Control>
              </Form.Group>

              <Form.Group as={Col} controlId="select.dia">
                <Form.Label>Dia</Form.Label>
                <Form.Control size="sm" as="select" onChange={handleChange} value={dia}>
                  {opcions_dies}
                </Form.Control>
              </Form.Group>

              <Form.Group as={Col} controlId="select.mes" >
                <Form.Label>Mes</Form.Label>
                <Form.Control size="sm" as="select" onChange={handleChange} value={mes}>
                  {opcions_mesos}
                </Form.Control>
              </Form.Group>

              <Form.Group as={Col} controlId="select.any" >
                <Form.Label>Any</Form.Label>
                <Form.Control size="sm" as="select" onChange={handleChange} value={any}>
                  <option value="2020">2020</option>
                  <option value="2019">2019</option>
                  <option value="2018">2018</option>
                  <option value="2017">2017</option>
                  <option value="2016">2016</option>
                  <option value="2015">2015</option>
                </Form.Control>
            </Form.Group>
          </Form.Row>
        </Form>
      </Col>
      <Col md={1}>
        <Button variant="secondary" type="button" className="pl-4 pr-4 mt-4 " id={'form.restablir'} onClick={handleChange}>
              Restablir
          </Button>{' '}  
                    
      </Col>
      <Col md={1}>
         
        <Button variant="primary" type="button" className="pl-4 pr-4 mt-4 " id={'form.enviar'} onClick={handleSubmit}>
              Enviar
          </Button>{' '}             
      </Col>
  </Row>  
  );
}

export default FormController;