import React from 'react';
import { Container,Badge, Card, Row, Col, ButtonGroup, Button, Form, FormGroup } from 'react-bootstrap';
import FormController from './formcontroller';
import fetcher from './fetcher';
import checker from './checker';
import QualityMap from './map';
import ContaminationHoursLineChart from './contaminationhourslinechart'
import ContaminationDataTable from './contaminationtable'
import TrafficHoursLineChart from './traffichourslinechart'
import TrafficHoursBarChart from './traffichoursbarchart'
import CombinedHoursChart from './combinedhourschart'
import TrafficDataTable from './traffictable'
import { Ring, Roller, Default } from "react-awesome-spinners";

class MapController extends React.Component {

  constructor(props) {

    super(props);

    this.state = {
      indicador: '',
      contaminant: '',
      dia: '',
      mes: '',
      any: '',
      indicadors: [],
      contaminants: [],
      estacions: [],
      trams: [],
      resultats: [],
      combinacio: [],
      congestions: [],
      transit: [],
      info_indicador: null,
      info_contaminant: null,
      info_resultat: null,
      info_congestio: null,
      carregat: false,
      fallat_transit: false,
      fallat_contaminacio: false
    };
    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
    
  }

  componentDidMount() {
    this.clearSelections();
    this.getContaminants();
  }

  handleChange(event) {
    let id = event.target.id;
    let value = event.target.value;
    let name = id.split('.')[1];

    if(name=='contaminant'){
      this.setState({ 'contaminant' : value }, this.getIndicadors);
    }

    if(name=='indicador'){
      this.setState({ 'indicador' : value });
    }

    if(name=='dia'){
      this.setState({ 'dia' : value });
    }

    if(name=='mes'){
      this.setState({ 'mes' : value });
    }

    if(name=='any'){
      this.setState({ 'any' : value });
    }

    if(name=='restablir'){
      this.clearSelections();
    }
 
  }
  
  handleSubmit(event) {
    if(this.validate()){
      this.setState({ 'resultats' : [], 'info_resultat' : null, 'congestions' : [], 'info_congestio':null, 'combinacio' : [], 'transit' : [], 'fallat_transit':false, 'fallat_contaminacio':false });
      this.getResultats();
    }else{
      alert('Dades insuficients.');
    }
    
    event.preventDefault();
  }
  
  validate() {
    return (this.state.contaminant!='' && this.state.indicador!='' && this.state.dia!='' && this.state.mes!='' && this.state.any!='' );
  }

  clearSelections(){
    let currentDate = new Date();
    let dia = currentDate.getDate();
    let mes = currentDate.getMonth() + 1;
    let any = currentDate.getFullYear();
    this.setState({
        'indicador': '',
        'contaminant': '',
        'dia': dia,
        'mes': mes,
        'any': any,
        'indicadors' : [],
        'contaminants' : [],
        'resultats' : [],
        'combinacio' : [],
        'congestions' : [],
        'transit': [],
        'info_indicador': null,
        'info_contaminant': null,
        'info_resultat': null,
        'info_congestio': null,
        'carregat': false,
        'fallat_transit':false,
        'fallat_contaminacio':false

      }, this.getContaminants);
  }

  getTrams = async () => {
    try {
      let trams = await fetcher('/trams');
        if (trams.length>0) {
            console.log("Trams recuperats");
            trams.forEach(function(tram){
              delete tram['created_at'];
              delete tram['updated_at'];
            }); 
            this.setState({ 'trams' : trams},this.mergeCongestionsWithSections);
        }else {
            console.log('Trams no recuperats');
        }
        console.log("Resposta trams:" , trams)
    }catch(error){
       console.log("Error obtenint trams", error);
    }
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

getEstacions = async () => {
  try {   
    let estacions = await fetcher('/contaminants/'+this.state.contaminant+'/estacions');
      if (estacions.length>0) {
          console.log("Estacions recuperades");
          estacions.forEach(function(estacio){
            delete estacio['created_at'];
            delete estacio['updated_at'];
          }); 
          this.setState({ 'estacions' : estacions },this.mergeResultsWithStations);
      }else {
        alert('Contaminant sense estacions.');
      }
      console.log("Resposta estacions:" , estacions)
  }catch(error){
     console.log("Error obtenint estacions del contaminant "+this.state.contaminant, error);
  }
}

getResultats = async () => {
  try {   
    let resultats = await fetcher('/contaminants/'+this.state.contaminant+'/resultats?dia='+this.state.dia+'&mes='+this.state.mes+'&any='+this.state.any+'&indicador_id='+this.state.indicador);
    resultats = Object.values(resultats); 
    if (resultats.length>0) {
          console.log("Resultat recuperats");
          resultats.forEach(function(resultat){
            delete resultat['created_at'];
            delete resultat['updated_at'];
          }); 
          this.setState({ 'resultats' : resultats },this.getContaminant);
      }else {
        console.log('Contaminant sense resultat.');
        this.setState({ 'fallat_contaminacio' : true });
      }
      console.log("Resposta resultats:" , resultats)
  }catch(error){
     console.log("Error obtenint resultat del contaminant "+this.state.contaminant, error);
  }

  try {   
    let congestions = await fetcher('/congestions?dia='+this.state.dia+'&mes='+this.state.mes+'&any='+this.state.any);
    congestions = Object.values(congestions); 
    if (congestions.length>0) {
          console.log("Congestions recuperades");
          congestions.forEach(function(congestio){
            delete congestio['created_at'];
            delete congestio['updated_at'];
          }); 
          this.setState({ 'congestions' : congestions }, this.getTrams);
      }else {
        console.log('Congestions sense resultat.');
        this.setState({ 'fallat_transit' : true });
      }
      
      console.log("Resposta Congestions:" , congestions)
  }catch(error){
     console.log("Error obtenint Congestions  ", error);
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

getResultat = async () => {
  try {
    let endpoint = '/resultats'
    let nom = 'dia'
  
    endpoint = endpoint + '/dies'
  
    let contaminant = this.state.contaminant
    let indicador = this.state.indicador   
    let condicio_contaminant = contaminant==0?'':'&contaminant_id='+contaminant
    let condicio_indicador = indicador==0?'':'&indicador_id='+indicador
    let condicions = condicio_contaminant+condicio_indicador
    let data = new Date((this.state.dia).toString()+'-'+(1+this.state.mes).toString()+'-'+this.state.any.toString())
    data = new Date(this.state.any, this.state.mes-1, this.state.dia)
    data.setHours(data.getHours()+2)
    let resultats = await fetcher(endpoint+'?start_date='+data.toISOString()+'&end_date='+data.toISOString()+condicions);
    resultats = Object.values(resultats); 
    if (resultats.length>0) {
          console.log("Resultats "+nom+ " recuperats");
          resultats.forEach(function(resultat){
            delete resultat['created_at'];
            delete resultat['updated_at'];
          }); 
          this.setState({ 'info_resultat' : resultats[0]});
      }else {
        console.log('Sense resultat '+nom+'.');
      }
      
      console.log("Resposta resultat "+nom+":" , resultats)
  }catch(error){
      console.log("Error obtenint resultat del contaminant "+this.state.contaminant, error);
  }

  try {
    let endpoint = '/congestions'
    let nom = 'dia'
  
    endpoint = endpoint + '/dies'
    let data = new Date((this.state.dia).toString()+'-'+(1+this.state.mes).toString()+'-'+this.state.any.toString())
    data = new Date(this.state.any, this.state.mes-1, this.state.dia)
    data.setHours(data.getHours()+2)
    let congestions = await fetcher(endpoint+'?start_date='+data.toISOString()+'&end_date='+data.toISOString());
    congestions = Object.values(congestions); 
    if (congestions.length>0) {
          console.log("Resultats transit "+nom+ " recuperats");
          congestions.forEach(function(congestio){
            delete congestio['created_at'];
            delete congestio['updated_at'];
          }); 
          this.setState({ 'info_congestio' : congestions[0]});
      }else {
        console.log('Sense estat transit '+nom+'.');
      }
      
      console.log("Resposta transit "+nom+":" , congestions)
  }catch(error){
      console.log("Error obtenint estat tranist", error);
  }
}

mergeResultsWithStations(){
  let resultats = this.state.resultats
  let estacions = this.state.estacions
  let combinacio = []
  for (const res in resultats) {
    combinacio[res] = []
    let estacio = estacions.filter(function(est) {return est['id']==resultats[res]['estacio_id']})
    combinacio[res].push(resultats[res])
    if(estacions[res]!=null){
      if(resultats[res]['estacio_id']==estacions[res]['id']){
        combinacio[res].push(estacions[res])
      }else{
        for (const est in estacions){
          if(estacions[est]['id']==resultats[res]['estacio_id']){
            estacio = estacions[est]
            break
          }
        }
        combinacio[res].push(estacio)
      }
    } 
  }
  this.setState({ 'combinacio' : combinacio }, this.getResultat);
}

mergeCongestionsWithSections(){
  let congestions = this.state.congestions
  let trams = this.state.trams
  let combinacio = []
  if(congestions.length>0 && trams.length>0){
    for (const res in congestions) {
      combinacio[res] = []
      let tram = trams.filter(function(tram) {return tram['id']==congestions[res]['tram_id']})
      combinacio[res].push(congestions[res])
      if(trams[res]!=null){
        if(congestions[res]['tram_id']==trams[res]['id']){
          combinacio[res].push(trams[res])
        }else{
          for (const t in trams){
            if(trams[t]['id']==congestions[res]['tram_id']){
              tram = trams[t]
              break
            }
          }
          combinacio[res].push(tram)
        }
      } 
    }
    console.log("mergeCongestionsWithSections",combinacio);
    this.setState({ 'transit' : combinacio });
  }
}

  render() {
    return (   
      <Container fluid> 
        
        <FormController 
          contaminants = {this.state.contaminants} 
          indicadors = {this.state.indicadors}
          contaminant = {this.state.contaminant}
          indicador = {this.state.indicador}
          dia = {this.state.dia}
          mes = {this.state.mes}
          any = {this.state.any}  
          handleChange={this.handleChange}
          handleSubmit={this.handleSubmit}
        />  <br/>
        <Row>
              <Col md={3}>
              <Button variant="secondary" className="text-light w-100" disabled>
         <strong>Data: </strong> <Badge variant="light">{this.state.info_resultat==null?(this.state.fallat_contaminacio?'Sense dades':'Carregant...'):this.state.info_resultat['Data']}</Badge>
        </Button>  
                          
            </Col>
            <Col md={3}>
            <Button variant="info" className="text-light w-100" disabled>
         <strong>Contaminant: </strong> <Badge variant="light">{this.state.info_contaminant==null?(this.state.fallat_contaminacio?'Sense dades':'Carregant...'):this.state.info_contaminant['simbol']}</Badge>
        </Button>           
            </Col>
            <Col md={3}>
             <Button variant={qualificationStyle(this.state.info_resultat==null?null:this.state.info_resultat['Qualificacio'],this.state.info_indicador==null?'':this.state.info_indicador['nom'])} className="text-light w-100" disabled>
        <strong className="">Estat aire </strong>: <Badge variant="light">{this.state.info_resultat==null?(this.state.fallat_contaminacio?'Sense dades':'Carregant...'):this.state.info_resultat['Qualificacio']}</Badge>
        </Button>             
            </Col>
            <Col md={3}>
                
            <Button variant={qualificationStyle(this.state.info_congestio==null?null:this.state.info_congestio['Actual'],'transit')} className="text-light w-100" disabled>
        <strong>Estat trànsit:</strong> <Badge variant="light">{this.state.info_congestio==null?(this.state.fallat_transit?'Sense dades':'Carregant...'):this.state.info_congestio['Actual']}</Badge>
        </Button>              
            </Col>
        </Row> 

    {(!this.state.carregat || (this.state.combinacio.length<1 || (this.state.congestions.length<1&&!this.state.carregat) || (this.state.transit.length<1&&!this.state.carregat) || (this.state.info_congestio==null&&!this.state.carregat)))?<><br/><br/><br/>{this.state.fallat_contaminacio?<h4 className="center text-center">No hi ha resultats per a la data especificada.</h4>:<div className="spinner"><br/><br/><br/><br/><Roller /></div>}</>:<>
          <div id = 'mapid'className="mt-3">
              <QualityMap 
                resultats = {this.state.resultats}
                estacions = {this.state.estacions} 
                combinacio = {this.state.combinacio}
                transit = {this.state.transit}
                congestions = {this.state.congestions}
                trams = {this.state.trams}
                info_indicador = {this.state.info_indicador}
                info_contaminant = {this.state.info_contaminant}
              />
          </div>
         <br/><br/>
        <Row>
          <Col>
            <CombinedHoursChart
                mostraestacions = {true}
                mostratitol = {true}
                label = {"Contaminació i trànsit"}
                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                congestions = {[this.state.info_congestio]}
                resultats = {this.state.resultats}
                estacions = {this.state.estacions} 
                info_indicador = {this.state.info_indicador}
                info_contaminant = {this.state.info_contaminant}
                height = {400}
              />
          </Col>
        </Row> 
        <br/><br/>
        <Row>
          <Col>
            <ContaminationHoursLineChart 
                mostraestacions = {true}
                mostratitol = {true}
                label = {"Qualitat de l'aire"}
                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                resultats = {this.state.resultats}
                estacions = {this.state.estacions} 
                info_indicador = {this.state.info_indicador}
                info_contaminant = {this.state.info_contaminant}
                height = {500}
              />
          </Col>
        </Row>  
        <hr/>
        <Row>
          <Col>
            <ContaminationDataTable 
                map={true}
                mostrallegenda = {true}
                mostratitol = {true}
                resultats = {this.state.resultats}
                capçalera = {[ "ESTACIÓ", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H",  "MAXIM", "MINIM", "MITJANA", "QUALIF."]}
                estacions = {this.state.estacions} 
                info_indicador = {this.state.info_indicador}
                info_contaminant = {this.state.info_contaminant}
              />
          </Col>
        </Row>
        <br/><br/>
        <Row>
          <Col>
            <TrafficHoursBarChart 
                mostraestacions = {true}
                mostratitol = {true}
                label = {"Estat del trànsit"}
                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                resultats = {[this.state.info_congestio]}
                height = {400}
              />
          </Col>
        </Row> 
        <hr/>
        <Row>
          <Col>
            <TrafficDataTable 
                map={true}
                mostrallegenda = {true}
                mostratitol = {true}
                resultats = {this.state.congestions}
                capçalera = {[ "TRAM", "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H",  "MITJANA"]}
                trams = {this.state.trams} 
              />
          </Col>
        </Row>
      </>}    
      </Container>
    );
  }
}

const qualificationStyle = (qualification, indicador) => { 
  if(indicador=='transit'){
    switch(qualification) {
      case 'Molt fluid':
        return 'primary';
      case 'Fluid':
        return 'success';
      case 'Dens':
        return 'warning';
      case 'Molt dens':
        return 'danger';
      case 'Congestionat':  
        return 'purple';
      case 'Tallat':  
        return 'bg-secondary';
      default:
        return 'dark';
    }
  }else{
    if(qualification == null) {
      return 'dark';
    }
    if(indicador == 'IQAM'){
      switch(qualification) {
        case 'bo':
          return 'primary';
        case 'moderat':
          return 'success';
        case 'regular':
          return 'warning';
        case 'dolent':
          return 'danger';
        case 'molt_dolent':
        case 'molt dolent':  
          return 'purple';
        default:
          return 'dark';
      }
    }else{
      switch(qualification) {
        case 'bo':
          return 'success';
        case 'moderat':
          return 'warning';
        case 'regular':
          return 'danger';
        case 'dolent':
          return 'purple';
        case 'molt_dolent':
        case 'molt dolent': 
          return 'purple';
        default:
          return 'dark';
      }
    }
  }
}

export default MapController;