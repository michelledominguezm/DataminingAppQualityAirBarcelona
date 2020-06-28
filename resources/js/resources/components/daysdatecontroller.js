import React from 'react';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import { registerLocale, setDefaultLocale } from  "react-datepicker";
import ca from 'date-fns/locale/ca';
registerLocale('es', ca)
import { Row, Col, Form, FormGroup, Button, ButtonGroup } from 'react-bootstrap';

const DaysDateController = (props) => {
  let opcions_contaminants = props.contaminants.length>0?props.contaminants.map((contaminant) =>
  <option 
    key={contaminant.id}
    value={contaminant.id}
  >
    {contaminant.simbol}
  </option>
):'';

let opcions_indicadors = props.indicadors.length>0? props.indicadors.map((indicador) =>
  <option 
    key={indicador.id}
    value={indicador.id}
  >
    {indicador.nom}
  </option>
):'';
    return(
      <Row>
        <Col md={1} className={' center'}>
            <Form.Label>Contaminant</Form.Label>
            <Form.Control size="sm" as="select" onChange={props.handleChange} value={props.contaminant} id="select.contaminant">
            {/*<option 
              key={'0'}
              value={0}
            >
              {'TOTS'}
            </option>*/}
              {opcions_contaminants}
            </Form.Control>
          </Col>
         <Col md={1} className={' center'}>
            <Form.Label>Indicador</Form.Label>
            <Form.Control size="sm" as="select" onChange={props.handleChange} value={props.indicador} id="select.indicador">
              {/*<option 
              key={'0'}
              value={0}
            >
              {'TOTS'}
            </option>*/}
              {opcions_indicadors}
            </Form.Control>
        </Col>
        <Col md={2} className={'center'}>
            <Form.Label>Data 1</Form.Label>  <br/>
            <DatePicker
              selected={props.start}
              onChange={date => props.setStartDate(date)}
              maxDate={new Date()}
              dateFormat="dd/MM/yyyy"
              locale="es"
            />
          </Col>
          <Col md={2} className={'center'}>
          <Form.Label>Data 2</Form.Label> <br/>
            <DatePicker
              selected={props.end}
              onChange={date => props.setEndDate(date)}
              minDate={props.start} 
              maxDate={new Date()} 
              locale="es"
              dateFormat="dd/MM/yyyy"/>
              
        </Col>
        <Col md={1} className={'mt-4 center'}>
          <Form>
            <Form.Check 
              type="switch"
              label="Contaminació"
              id="select.toggle_contaminant" 
              onChange={props.handleChange}
              checked={props.get_contaminant}
              
            />
          </Form>
        </Col>
        <Col md={1} className={'mt-4 center'}>
          <Form>
            <Form.Check 
              type="switch"
              label="Transit"
              id="select.toggle_transit" 
              onChange={props.handleChange}
              checked={props.get_transit}
            />
          </Form>
        </Col>
        <Col md={2} className={'center'}>
          <Button variant="primary" type="button" className="mt-4 pl-4 pr-4" size="sm" id={'date.enviar'} onClick={props.handleSubmit}>
                Enviar
            </Button>{' '}             
        </Col>
      </Row>  
      );
}

export default DaysDateController;