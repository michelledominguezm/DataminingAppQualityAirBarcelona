import React from 'react';
import ReactDOM from 'react-dom';
import Navigation from './resources/nav';
import { Container } from 'react-bootstrap';

class App extends React.Component {
  render() {
     return (
      <Container fluid> 
        <Navigation/>
      </Container>
     );
  }
}
export default App;

if (document.getElementById('app')) {
  ReactDOM.render(
    <App />,
    document.getElementById('app')
  );
}
