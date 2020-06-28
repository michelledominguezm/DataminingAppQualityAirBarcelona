import React from 'react';
import { ButtonGroup, ToggleButton, Row, Col} from 'react-bootstrap';

const PeriodToggle = (props) => {
  const periodes = [
    { name: 'Elecció lliure', value: 'lliure' },
    { name: 'Abans de l\'estat d\'alarma', value: 'abans' },
    { name: 'Abans de la desescalada', value: 'durant' },
    { name: 'Desescalada: Fase 0', value: '0' },
    { name: 'Desescalada: Fase 1', value: '1' },
    { name: 'Desescalada: Fase 2', value: '2' },
    { name: 'Desescalada: Fase 3', value: '3' },
    { name: 'Després de la desescalada', value: 'despres' },
    
  ];
    return(
      <Row>
        <Col>
        <ButtonGroup toggle>
        {periodes.map((periode, idx) => (
          <ToggleButton
            key={idx}
            type="radio"
            variant="outline-info"
            name="radio"
            value={periode.value}
            checked={props.periode === periode.value}
            onChange={props.handleChange}
          >
            {periode.name}
          </ToggleButton>
        ))}
      </ButtonGroup>
        </Col>
      
      </Row>
      );
}

export default PeriodToggle;