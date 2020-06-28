import React from 'react';
import { Container,Accordion, Card, Row, Col, ButtonGroup, Button, Form, FormGroup, ToggleButton} from 'react-bootstrap';

import fetcher from './fetcher';

import { Ring, Roller, Default } from "react-awesome-spinners";

import PeriodDateController from './perioddatecontroller'
import PeriodToggle from './periodtoggle'
import DaysDateController from './daysdatecontroller'

import ContaminationDataTable from './contaminationtable'
import ContaminationHoursLineChart from './contaminationhourslinechart'
import ContaminationDaysLineChart from './contaminationdayslinechart'
import TrafficHoursBarChart from './traffichoursbarchart'
import TrafficDaysBarChart from './trafficdaysbarchart'
import TrafficDataTable from './traffictable'
import CombinedHoursChart from './combinedhourschart'
import CombinedDaysChart from './combineddayschart'

class ComparatorController extends React.Component {

  constructor(props) {

    super(props);

    this.state = {
      indicador: '',
      contaminant: '',
      inici: new Date(((new Date()).setDate((new Date()).getDate()-1))),
      final: new Date(),
      indicadors: [],
      contaminants: [],
      resultats: [],
      congestions:[],
      info_indicador: null,
      info_contaminant: null,
      is_period: true,
      get_contaminant: true,
      get_transit: true,
      periode: 'lliure',
      carregat:false,
      hourly: false,
    };
    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);

    this.setStartDate = this.setStartDate.bind(this);
    this.setEndDate = this.setEndDate.bind(this);
    
    this.switchComparator = this.switchComparator.bind(this);
    this.switchScale = this.switchScale.bind(this);

    this.setPeriode = this.setPeriode.bind(this);
  }

  componentDidMount() {
    this.getContaminants();
  }

  setStartDate(date){
    this.setState({ 'inici' : date });
  }

  setEndDate(date){
    this.setState({ 'final' : date });
  }
  

  handleChange(event) {
    //console.log("On handle change")
    let id = event.target.id;
    let value = event.target.value;
    let name = id.split('.')[1];
    if(name=='contaminant'){
      if (value!=0){
        this.setState({ 'contaminant' : value }, this.getIndicadors);
      }else{
        this.setState({ 'contaminant' : value,
                        'indicador' : value,
                        'indicadors':[]});
      }
      
    }
    else if(name=='indicador'){
      this.setState({ 'indicador' : value });
    }
    else if(name=='toggle_contaminant'){
      this.setState({ 'get_contaminant' : !this.state.get_contaminant });
    }
    else if(name=='toggle_transit'){
      this.setState({ 'get_transit' : !this.state.get_transit });
    }
  }
  
  handleSubmit(event) {
    this.state.inici.setHours(this.state.inici.getHours()+2)
    this.state.final.setHours(this.state.final.getHours()+2)
    //console.log("handleSubmit")

    if(this.validate()){
      this.setState({ 'resultats' : [] , 'congestions' : [], 'carregat':false});
      this.getContaminant();
      this.getResultats();
    }else{
      alert('Dades insuficients.');
    }
    event.preventDefault();
  }
  
  validate() {
    return (this.state.contaminant!='' && this.state.indicador!='' && this.state.inici!='' && this.state.final );
  }
  getContaminants = async () => {
    try {
      let contaminants = await fetcher('/contaminants');
        if (contaminants.length>0) {
            console.log("Contaminants recuperats");
            contaminants.forEach(function(contaminant){
              delete contaminant['created_at'];
              delete contaminant['updated_at'];
            }); 
            this.setState({ 'contaminants' : contaminants, 'contaminant' : contaminants[0]['id'] }, this.getIndicadors);
        }else {
            console.log('Contaminants no recuperats');
        }
        console.log("Resposta contaminants:" , contaminants)
    }catch(error){
       console.log("Error obtenint contaminants", error);
    }
}

getIndicadors = async () => {
  try {   
    let indicadors = await fetcher('/contaminants/'+this.state.contaminant+'/indicadors');
      if (indicadors.length>0) {
          console.log("Indicadors recuperats");
          indicadors.forEach(function(indicador){
            delete indicador['created_at'];
            delete indicador['updated_at'];
          }); 
          this.setState({ 'indicadors' : indicadors, 'indicador' : indicadors[0]['id'] });

          if (this.state.resultats.length<1){
            this.getResultats();
          }
      }else {
        alert('Contaminant sense indicador. Es procedeix a eliminar-lo de les opcions. Si us plau, sel·leccioni un altre contaminant.');
        let contaminants = this.state.contaminants;
        let id = this.state.contaminant;
        contaminants = contaminants.filter(function(contaminant) {
            return contaminant['id'] != id;
        });
          this.setState({ 'indicadors' : [], 'contaminants' : contaminants, 'contaminant': contaminants[0]['id']}, this.getIndicadors);
          console.log('Indicadors del contaminant '+this.state.contaminant+' no recuperats');
      }
      console.log("Resposta indicadors:" , indicadors)
  }catch(error){
     console.log("Error obtenint indicadors del contaminant "+this.state.contaminant, error);
  }
}

getResultats = async () => {
    try {
      let endpoint = '/resultats'
      let nom = 'dies'
      if(this.state.is_period){
        endpoint = endpoint + '/periode'
        nom = 'periode'
      }else{
        endpoint = endpoint + '/dies'
      }
      let contaminant = this.state.contaminant
      let indicador = this.state.indicador   
      let condicio_contaminant = contaminant==0?'':'&contaminant_id='+contaminant
      let condicio_indicador = indicador==0?'':'&indicador_id='+indicador
      let condicions = condicio_contaminant+condicio_indicador
      let resultats = await fetcher(endpoint+'?start_date='+this.state.inici.toISOString()+'&end_date='+this.state.final.toISOString()+condicions);
      resultats = Object.values(resultats); 
      if (resultats.length>0) {
            console.log("Resultats "+nom+ " recuperats");
            resultats.forEach(function(resultat){
              delete resultat['created_at'];
              delete resultat['updated_at'];
            }); 
            this.setState({ 'resultats' : resultats },this.getContaminant);
        }else {
          console.log('Sense resultats '+nom+'.');
        }
        
        console.log("Resposta resultats "+nom+":" , resultats)
    }catch(error){
       console.log("Error obtenint resultats del contaminant "+this.state.contaminant, error);
    }

    try {
      let endpoint = '/congestions'
      let nom = 'dies'
      if(this.state.is_period){
        endpoint = endpoint + '/periode'
        nom = 'periode'
      }else{
        endpoint = endpoint + '/dies'
      }

      let congestions = await fetcher(endpoint+'?start_date='+this.state.inici.toISOString()+'&end_date='+this.state.final.toISOString());
      congestions = Object.values(congestions); 
      if (congestions.length>0) {
            console.log("Resultats "+nom+ " recuperats");
            congestions.forEach(function(resultat){
              delete congestions['created_at'];
              delete congestions['updated_at'];
            }); 
            this.setState({ 'congestions' : congestions });
        }else {
          console.log('Sense resultats transit '+nom+'.');
        }
        
        console.log("Resposta resultats transit "+nom+":" , congestions)
    }catch(error){
       console.log("Error obtenint resultats transit del contaminant "+this.state.contaminant, error);
    }
  this.setState({ 'carregat' : true });
}

getContaminant() {
  let contaminant_id = this.state.contaminant
  let contaminants = this.state.contaminants
  let contaminant = contaminants.filter((contaminant) => {                        
    return contaminant['id'] == contaminant_id
  })
  this.setState({ 'info_contaminant' : contaminant[0] },this.getIndicador);
}

getIndicador() {
  let indicador_id = this.state.indicador
  let indicadors = this.state.indicadors
  let indicador = indicadors.filter((indicador) => {                        
    return indicador['id'] == indicador_id
  })
  this.setState({ 'info_indicador' : indicador[0] }, this.getEstacions);
}

switchComparator(){
  this.setState({ 'is_period' : !this.state.is_period, 'resultats':[] ,congestions:[],'carregat':false, 'hourly':false},this.getResultats);
}

switchScale(){
  this.setState({ 'hourly' : !this.state.hourly});
}

setPeriode(event){
  //console.log("On set periode")
  let value = event.target.value;
  let inici = null
  let final = null
  
  switch(value) {
    case 'lliure':
      inici = new Date(((new Date()).setDate((new Date()).getDate()-1)))
      final = new Date()
      break;
    case 'abans':
      inici = new Date(2020, 1-1, 1)
      final = new Date(2020, 3-1, 13)
      break;
    case 'durant':
      inici = new Date(2020, 3-1, 14)
      final = new Date(2020, 5-1, 3)
      break;
    case '0':
      inici = new Date(2020, 5-1, 4)
      final = new Date(2020, 5-1, 23)
      break;
    case '1':
      inici = new Date(2020, 5-1, 24)
      final = new Date(2020, 6-1, 7)
      break;
    case '2':
      inici = new Date(2020, 6-1, 8)
      final = new Date(2020, 6-1, 17)
      break;
    case '3':
      inici = new Date(2020, 6-1, 18)
      final = new Date(2020, 6-1, 18)
      break;
    case 'despres':
      inici = new Date(2020, 6-1, 19)
      final = new Date(2020, 6-1, 30)
        break;
    default:
      inici = new Date(((new Date()).setDate((new Date()).getDate()-1)))
      final = new Date()
      break;
  }
  //console.log("inici set",inici)
  //console.log("final set",final)
  this.setState({ 'periode' : value,'inici' : inici,'final' : final});
}

  render() {
    return (   
      <Container fluid id=''> 
        <ButtonGroup toggle>
        
          <ToggleButton
            key={'1'}
            type="radio"
            variant="outline-dark"
            name="radio"
            value={'periodes'}
            checked={this.state.is_period}
            onChange={this.switchComparator}
          >
            {'Comparació de periodes'}
          </ToggleButton>
          <ToggleButton
            key={'2'}
            type="radio"
            variant="outline-dark"
            name="radio"
            value={'dies'}
            checked={!this.state.is_period}
            onChange={this.switchComparator}
          >
            {'Comparació de dies'}
          </ToggleButton>
      </ButtonGroup>
      <br/><br/>
        {this.state.is_period?
        <><PeriodToggle 
          periode = {this.state.periode}
          handleChange={this.setPeriode}
        
        /><br/><br/></>
        :
        null}
      
        {this.state.is_period? <>
          <PeriodDateController 
            contaminants = {this.state.contaminants} 
            indicadors = {this.state.indicadors}
            contaminant = {this.state.contaminant}
            indicador = {this.state.indicador}
            start = {this.state.inici}
            end = {this.state.final}
            setStartDate={this.setStartDate}
            setEndDate={this.setEndDate}
            handleChange={this.handleChange}
            handleSubmit={this.handleSubmit}
            get_contaminant = {this.state.get_contaminant}
            get_transit = {this.state.get_transit}
          />
          <br/><br/>
          <ButtonGroup toggle className='w-100'>
            <ToggleButton
              key={'2'}
              type="radio"
              variant="outline-secondary"
              name="radio"
              value={'dia'}
              checked={!this.state.hourly}
              onChange={this.switchScale}
            >
              {'Mostrar resultats per dies'}
            </ToggleButton>
            <ToggleButton
              key={'1'}
              type="radio"
              variant="outline-secondary"
              name="radio"
              value={'hora'}
              checked={this.state.hourly}
              onChange={this.switchScale}
            >
              {'Mostrar resultats per hores'}
            </ToggleButton>
          </ButtonGroup>
          </>:
          <DaysDateController 
            contaminants = {this.state.contaminants} 
            indicadors = {this.state.indicadors}
            contaminant = {this.state.contaminant}
            indicador = {this.state.indicador}
            start = {this.state.inici}
            end = {this.state.final}
            setStartDate={this.setStartDate}
            setEndDate={this.setEndDate}
            handleChange={this.handleChange}
            handleSubmit={this.handleSubmit}
            get_contaminant = {this.state.get_contaminant}
            get_transit = {this.state.get_transit}
          />
        } 
        
        {(this.state.get_contaminant && this.state.resultats.length<1 && !this.state.carregat)|| (this.state.get_transit && this.state.congestions.length<1 && !this.state.carregat)?<><br/><div className="spinner"><br/><br/><Roller /></div></>:<>
        <br/><br/>
        {this.state.is_period && !this.state.hourly?<>
          <Row>
            <Col>
              
              {(this.state.get_transit && this.state.get_contaminant)? <> 
                <CombinedDaysChart
                    mostraestacions = {false}
                    mostratitol = {true}
                    label = {"Contaminació i trànsit"}
                    labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                    congestions = {this.state.congestions}
                    resultats = {this.state.resultats}
                    estacions = {this.state.estacions} 
                    info_indicador = {this.state.info_indicador}
                    info_contaminant = {this.state.info_contaminant}
                    //trams = {this.state.trams} 
                    height = {400}
                  />
                </>:null}
            </Col>
          </Row>
          <br/>
          <hr/><hr/>
          <br/>
          <Row>
            <Col>
              {this.state.get_contaminant?<>
                <ContaminationDaysLineChart 
                          mostratitol = {true}
                          mostraestacions = {false}
                          label = {"Qualitat de l'aire"}
                          labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                          resultats = {this.state.resultats}
                          //estacions = {this.state.estacions} 
                          info_indicador = {this.state.info_indicador}
                          info_contaminant = {this.state.info_contaminant}
                          height = {450}
                        />
                <ContaminationDataTable 
                    map={false}
                    mostrallegenda = {true}
                    mostratitol = {true}
                    resultats = {this.state.resultats}
                    capçalera = {[ "DATA", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H", "MITJANA", "QUALIF."]}
                    //estacions = {this.state.estacions} 
                    info_indicador = {this.state.info_indicador}
                    info_contaminant = {this.state.info_contaminant}
                  /> <hr/>
                </>
              :null}
              <br/>
             
              <br/>
              {this.state.get_transit?<><hr/>
                <TrafficDaysBarChart 
                  mostraestacions = {false}
                  mostratitol = {true}
                  label = {"Estat del trànsit"}
                  labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                  resultats = {this.state.congestions}
                  //trams = {this.state.trams} 
                  height = {400}
                /> 
                <TrafficDataTable 
                  map={false}
                  mostrallegenda = {true}
                  mostratitol = {true}
                  resultats = {this.state.congestions}
                  capçalera = {[ "DATA", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H",  "MITJANA"]}
                  trams = {this.state.trams} 
                />
              </>
              :null}
          </Col>
        </Row> 
        
        </>:<>

        <Row>
          <Col>
            {(this.state.get_transit && this.state.get_contaminant)? <> 
              <CombinedHoursChart
                  mostraestacions = {false}
                  mostratitol = {true}
                  label = {"Contaminació i trànsit"}
                  labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                  congestions = {this.state.congestions}
                  resultats = {this.state.resultats}
                  estacions = {this.state.estacions} 
                  info_indicador = {this.state.info_indicador}
                  info_contaminant = {this.state.info_contaminant}
                  //trams = {this.state.trams} 
                  height = {400}
                /><hr/>
              </>:null}
          </Col>
        </Row>
        <br/>
        
        <br/>
        <Row>
          <Col>
          {this.state.get_contaminant?<>
            <hr/>
            <ContaminationHoursLineChart 
                      mostratitol = {true}
                      mostraestacions = {false}
                      label = {"Qualitat de l'aire"}
                      labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                      resultats = {this.state.resultats}
                      //estacions = {this.state.estacions} 
                      info_indicador = {this.state.info_indicador}
                      info_contaminant = {this.state.info_contaminant}
                      height = {450}
                    />
            <ContaminationDataTable 
                map={false}
                mostrallegenda = {true}
                mostratitol = {true}
                resultats = {this.state.resultats}
                capçalera = {[ "DATA", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H", "MITJANA", "QUALIF."]}
                //estacions = {this.state.estacions} 
                info_indicador = {this.state.info_indicador}
                info_contaminant = {this.state.info_contaminant}
              /><hr/>
            </>
            :
            null
            }
            <br/>
            
            <br/>
            {this.state.get_transit?<><hr/>
              <TrafficHoursBarChart 
                mostraestacions = {false}
                mostratitol = {true}
                label = {"Estat del trànsit"}
                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                resultats = {this.state.congestions}
                //trams = {this.state.trams} 
                height = {400}
              /> 
              <TrafficDataTable 
                map={false}
                mostrallegenda = {true}
                mostratitol = {true}
                resultats = {this.state.congestions}
                capçalera = {[ "DATA", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H",  "MITJANA"]}
                trams = {this.state.trams} 
              />
            </>
            :
            null
            }
          </Col>
        </Row> 
        
        </>}     
        
      </>}
        
      </Container>
    );
  }
}

export default ComparatorController;