import React from 'react';
import ContentMapa from './contentmapa';
import ContentGrafics from './contentgrafics';
import { Nav, Navbar, NavDropdown } from 'react-bootstrap';
import {
	BrowserRouter as Router,
	Switch,
	Route,
	Link
  } from "react-router-dom";

class Navigation extends React.Component {
   render() {
      return (	 
		<Router>
		<Navbar bg="primary" variant="dark" expand="lg">
		<Link to="/mapa"><Navbar.Brand href="/">
				<img
					alt=""
					src="/logo2.svg"
					width="30"
					height="30"
					className="d-inline-block align-top"
      			/>{' '}
	  			AIREBCN
			</Navbar.Brand></Link>
			<Navbar.Toggle aria-controls="basic-navbar-nav" />
			<Navbar.Collapse id="basic-navbar-nav">
				<Nav className="mr-auto">
				<Link to="/mapa"><Nav.Link href="#mapa">Mapa (dia) </Nav.Link></Link> 
				<Link to="/grafics"><Nav.Link href="#grafics">Grafics (periodes) </Nav.Link></Link>
				</Nav>
			</Navbar.Collapse>
		</Navbar>
		<Switch>
          <Route path="/grafics">
            <ContentGrafics />
          </Route>
          <Route path="/mapa">
            <ContentMapa />
          </Route>
		  <Route path="/">
            <ContentMapa />
          </Route>
        </Switch>
		</Router>
      );
   }
}

export default Navigation;