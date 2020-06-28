import React, { Component } from 'react'
import { Container } from 'react-bootstrap';
import { Map, TileLayer, Marker, Popup, Polygon } from 'react-leaflet'
import ContaminationDataTable from './contaminationtable'
import MapLegend from './maplegend'
import ContaminationHoursLineChart from './contaminationhourslinechart'
import TrafficHoursLineChart from './traffichourslinechart'
import TrafficHoursBarChart from './traffichoursbarchart'
import TrafficDataTable from './traffictable'



export default class QualityMap extends Component {
  constructor(props) {
    super(props);
    this.state = {
      lat: 41.3887901,
      lng: 2.1589899,
      zoom: 12,
      maxZoom: 20,
      showTrams: false
  }
    this.handleChange = this.handleChange.bind(this);
  }
   handleChange(e){
     this.setState({ 'showTrams' : !this.state.showTrams });
   }
   render() {
      const greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
      const blueIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
      
      const yellowIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-yellow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

      const redIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img//marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

      const blackIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-black.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

      const purpleIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
       return (
        this.props.resultats ?(
              <Map 
                 center={[this.state.lat, this.state.lng]} 
                 zoom={this.state.zoom}
                 maxZoom={this.state.maxZoom} 
                 style={{ width: '100%', height: '100%', margin: 'auto'}}
              >
              <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a> | Font de les dades: Generalitat de Catalunya.'
                subdomains= 'abcd'                
                url="https://{s}.basemaps.cartocdn.com/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png"
               />
              <MapLegend className=""  
                        info_indicador = {this.props.info_indicador} 
                        info_contaminant = {this.props.info_contaminant}
                        trams = {this.state.showTrams}
                        handleChange = {this.handleChange}
                        ></MapLegend>
              {
                this.props.combinacio.map(c => {
                  if(c[1]!=null){
                  let resultats = this.props.resultats.filter(function(res) {return res['estacio_id']==c[1]['id']})
                  const point = [c[1]['latitud'],c[1]['longitud']]
                  let i = blueIcon;
                  let indicador = c[0]['indicador_id'].split("_")[0]

                  if(indicador == 'IQAM'){
                    switch(c[0]['qualificacio']) {
                      case 'bo':
                        i = blueIcon
                        break;
                      case 'moderat':
                        i = greenIcon
                        break;
                      case 'regular':
                        i = yellowIcon
                        break;
                      case 'dolent':
                        i = redIcon
                        break;
                      case 'molt_dolent':
                      case 'molt dolent':
                        i = purpleIcon
                        break;
                      default:
                        i = blackIcon
                        break;
                    }
                  }else if (indicador == 'ICQA'){
                    switch(c[0]['qualificacio']) {
                      case 'bo':
                        i = greenIcon
                        break;
                      case 'moderat':
                        i = yellowIcon
                        break;
                      case 'regular':
                        i = redIcon
                        break;
                      case 'dolent':
                        i = purpleIcon
                        break;
                      case 'molt_dolent':
                      case 'molt dolent':
                        i = purpleIcon
                        break;
                      default:
                        i = blackIcon
                        break;
                    }
                  }
                  if (c[0]['maxim']!=""){
                  return (
                   
                      <Marker position={point} key={c[0]['id']} icon={i} >
                          <Popup>
                              <span><strong>Nom: </strong> {c[1]['nom_estacio']}</span>
                              <br/>
                              <span><strong>Ubicació: </strong></span>
                              
                              <span>{c[1]['ubicacio']}.</span> 
                              <br/>
                              <span><strong>Barri: </strong>{c[1]['nom_barri']}.</span>
                              <br/>
                              <br/>
                              <ContaminationDataTable
                                map={true} 
                                mostrallegenda = {false}
                                mostratitol = {false}
                                resultats = {resultats}
                                capçalera = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H",  "MAXIM", "MINIM", "MITJANA", "QUALIF."]}
                                info_indicador = {this.props.info_indicador}
                                info_contaminant = {this.props.info_contaminant}
                                estacions = {this.props.estacions}
                              />
                              <ContaminationHoursLineChart 
                                mostraestacions = {true}
                                mostratitol = {false}
                                label = {"Qualitat de l'aire"}
                                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                                resultats = {resultats}
                                estacions = {this.props.estacions} 
                                info_indicador = {this.props.info_indicador}
                                info_contaminant = {this.props.info_contaminant}
                                height = {250}
                              />
                          </Popup>
                      </Marker>
                    )}else{ return null}}
                  })
              }
              {
                this.state.showTrams==false?null:this.props.transit.map(c => {
                  let coords = c[1]['coordenades'].split(",")
                  let positions = []
                  for (let i = 0; i < coords.length; i+=2) {
                    let lat = parseFloat(coords[i+1]).toFixed(4)
                    let long = parseFloat(coords[i]).toFixed(4)
                    if(!isNaN(lat) && !isNaN(long)){
                      positions.push([lat,long])
                    }
                  }
                  let actual = this.getQualificacio(c[0]['actual'])
                  let previst = this.getQualificacio(c[0]['previst'])

                  let color = 'black';
                  switch(actual) {
                    case 'Molt fluid':
                      color = 'blue';
                      break;
                    case 'Fluid':
                      color = 'green';
                      break;
                    case 'Dens':
                      color = 'yellow';
                      break;
                    case 'Molt dens':
                      color = 'red';
                      break;
                    case 'Congestió':  
                    color = 'purple';
                      break;
                    case 'Tallat':  
                      color = 'grey';
                      break;
                    default:
                      color = 'black';
                  }
                  let resultats = this.props.congestions.filter(function(res) {return res['tram_id']==c[1]['id']})
                  console.log("transit mark",resultats)
                  return (
                      <Polygon positions={positions} key={c[1]['id']} color={color}>
                        <Popup>
                              <span><strong>Tram: </strong>{c[1]['descripcio']}</span>
                              <br/>
                              <span><strong>Estat actual: </strong>{actual}</span>
                              <br/>
                              <span><strong>Estat previst: </strong>{previst}</span>
                              <br/>
                              <br/>
                              <TrafficHoursBarChart 
                                mostraestacions = {true}
                                mostratitol = {false}
                                label = {"Estat del trànsit"}
                                labels = {[ "01H",  "02H",  "03H",  "04H",  "05H",  "06H",  "07H",  "08H",  "09H",  "10H",  "11H",  "12H",  "13H",  "14H",  "15H",  "16H",  "17H",  "18H",  "19H",  "20H",  "21H",  "22H",  "23H",  "24H"]}
                                resultats = {resultats}
                                height = {200}
                              />
                          </Popup>
                      </Polygon>
                    )
                  })
              }
             </Map>)
               :
               'Carregant resultats...'
        )
    }

    getQualificacio(valor) {
      if(valor==0){
        valor = "Sense dades";
      }else if(valor>0 && valor<1.5){
        valor = "Molt fluid";
      }else if(valor>=1.5 && valor<2.5){
        valor = "Fluid";
      }else if(valor>=2.5 && valor<3.5){
        valor = "Dens";
      }else if(valor>=3.5 && valor<4.5){
        valor = "Molt dens";
      }
      else if(valor>=4.5 && valor<5.5){
        valor = "Congestionat";
      }
      else if(valor>=5.5){
        valor = "Tallat";
      }else{
        valor = "Sense dades";
      }
      return valor
    }
    
}