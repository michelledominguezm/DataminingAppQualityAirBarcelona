import React from 'react';
import ReactDOM from 'react-dom';
import { Form, FormGroup } from 'react-bootstrap';
import { useLeaflet } from "react-leaflet";
import L from "leaflet";
import { useEffect } from "react";

const MapLegend = (props) => {
  const { map } = useLeaflet();
  useEffect(() => {
    let element = document.getElementsByClassName("info legend");
    element.length>0?element[0].remove():'';
    if(props.info_indicador!=null){
      const legend = L.control({ position: "bottomright" });

    legend.onAdd = () => {
      const div = L.DomUtil.create("div", "info legend");

      if(props.info_indicador['nom']=='IQAM'){
        ReactDOM.render(
          <div>
            <p><strong>Classificació segons l'{props.info_indicador==null?'Indicador':props.info_indicador['nom']} pel contaminant {props.info_contaminant==null?'Contaminant':props.info_contaminant['simbol']}</strong></p> 
            
             <i className="bg-primary"></i><i className="pl-2"> </i> Bo: {props.info_indicador['bo'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/><br/>
             <i className="bg-success"></i><i className="pl-2"> </i> Moderat: {props.info_indicador['moderat'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/><br/>
             <i className="bg-warning"></i><i className="pl-2"> </i> Regular: {props.info_indicador['regular'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/><br/>
             <i className="bg-danger"></i><i className="pl-2"> </i> Dolent: {props.info_indicador['dolent'].split("[")[0]} {props.info_contaminant['unitats']} 
             <br/><br/>
             <i className="purple"></i><i className="pl-2"> </i> Molt dolent: {props.info_indicador['molt_dolent'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/> <br/>
             <Form.Check type="checkbox" label="Veure trams de trànsit" checked={props.trams} onChange={props.handleChange}/>
             {props.trams?<>
              <small className="text-primary">Molt fluid</small> -
              <small className="text-success"> Fluid</small> -
              <small className="text-warning"> Dens</small> -
              <small className="text-danger"> Molt dens</small> -
              <small className="text-purple"> Congestionat</small> -
              <small className="text-secondary"> Tallat</small> -
              <small className="text-dark"> Sense dades</small> 
            </>:null}
          </div> 
          , div);
      }else if (props.info_indicador['nom']=='ICQA'){
        ReactDOM.render(
          <div>
            <p><strong>Classificació segons l'{props.info_indicador==null?'Indicador':props.info_indicador['nom']} pel contaminant {props.info_contaminant==null?'Contaminant':props.info_contaminant['simbol']}</strong></p> 
            
             <i className="bg-success"></i><i className="pl-2"> </i> Bo: {props.info_indicador['bo'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/><br/>
             <i className="bg-warning"></i><i className="pl-2"> </i> Regular: {props.info_indicador['moderat'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/><br/>
             <i className="bg-danger"></i><i className="pl-2"> </i> Dolent: {props.info_indicador['regular'].split("[")[0]} {props.info_contaminant['unitats']} 
             <br/><br/>
             <i className="purple"></i><i className="pl-2"> </i> Molt dolent: {props.info_indicador['dolent'].split("[")[0]} {props.info_contaminant['unitats']}
             <br/> <br/>
             <Form.Check type="checkbox" label="Veure trams de trànsit" checked={props.trams} onChange={props.handleChange}/>
             {props.trams?<>
              <small className="text-primary">Molt fluid</small> -
              <small className="text-success"> Fluid</small> -
              <small className="text-warning"> Dens</small> -
              <small className="text-danger"> Molt dens</small> -
              <small className="text-purple"> Congestionat</small> -
              <small className="text-secondary"> Tallat</small> -
              <small className="text-dark"> Sense dades</small> 
            </>:null}
          </div> 
          , div);
      }
      
        return div;
      };

    legend.addTo(map);
    }
    
  });
  return null;
};

export default MapLegend;
