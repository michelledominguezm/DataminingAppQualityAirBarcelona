import React, { Component } from 'react'
import { Table } from 'react-bootstrap';
import TableLegend from './tablelegend';
import { CSVLink } from "react-csv";
import checker from './checker';

const TrafficDataTable = (props) => { 
   let headers = ''
   let nom_indicador = ''
   let resultats = props.resultats
   let capçalera = props.capçalera

   let resultats_csv = []

   let data = '[sense especificar]';

   let title = ''
   let filename = ''
   
   if (resultats.length>0){
    data = resultats[0]["dia"]+'/'+resultats[0]["mes"]+'/'+resultats[0]["any"];
    headers = Object.keys(resultats[0])
    title = (props.map==false)?<p className={'font-weight-bold pt-3'}>Mostrant els estats de transit del periode especificat</p>:<p className={'font-weight-bold pt-3'}>Mostrant els estats de transit del {data}</p> 
    title = (props.mostratitol==true)?title:null
    
    if(capçalera==undefined){
      headers = headers.map((key, index) => {
        return <th key={index} className={'bg-light'}>{key.toUpperCase()}</th>
     })
    }else {
      headers = capçalera.map((key) => {
        return <th key={key} className={'bg-light'}>{key.toUpperCase()}</th>
     })
    }
   }
   let tram,t,nom,nom_csv = ''
   
   let table =  resultats.map((resultat, index) => {
      if(props.map){
        let { id, tram_id,any,mes,dia,H01,P01,H02,P02,H03,P03,H04,P04,H05,P05,H06,P06,H07,P07,H08,P08,H09,P09,H10,P10,H11,P11,H12,P12,H13,P13,H14,P14,H15,P15,H16,P16,H17,P17,H18,P18,H19,P19,H20,P20,H21,P21,H22,P22,H23,P23,H24,P24,actual,previst,complet} = resultat 
      
        t = props.trams.filter(function(res) {return res['id']==tram_id})
        nom = t.length==0?'nom':t[0]['descripcio']
        tram = <td className={'bg-info'}>{nom}</td>
        resultats_csv.push([nom,checkValue(H24),checkValue(H01),checkValue(H02),checkValue(H03),checkValue(H04),checkValue(H05),checkValue(H06),checkValue(H07),checkValue(H08),checkValue(H09),checkValue(H10),checkValue(H11),checkValue(H12),checkValue(H13),checkValue(H14),checkValue(H15),checkValue(H16),checkValue(H17),checkValue(H18),checkValue(H19),checkValue(H20),checkValue(H21),checkValue(H22),checkValue(H23),checkValue(actual)])
        filename = "Estat_transit_"+data+".csv"
        
        return (
            <tr key={id}>
              {tram}
              <td className={qualificationStyle(checkValue(H24))}>{checkValue(H24)}</td>
              <td className={qualificationStyle(checkValue(H01))}>{checkValue(H01)}</td>
              <td className={qualificationStyle(checkValue(H02))}>{checkValue(H02)}</td>
              <td className={qualificationStyle(checkValue(H03))}>{checkValue(H03)}</td>
              <td className={qualificationStyle(checkValue(H04))}>{checkValue(H04)}</td>
              <td className={qualificationStyle(checkValue(H05))}>{checkValue(H05)}</td>     
              <td className={qualificationStyle(checkValue(H06))}>{checkValue(H06)}</td>         
              <td className={qualificationStyle(checkValue(H07))}>{checkValue(H07)}</td>
              <td className={qualificationStyle(checkValue(H08))}>{checkValue(H08)}</td>
              <td className={qualificationStyle(checkValue(H09))}>{checkValue(H09)}</td>
              <td className={qualificationStyle(checkValue(H10))}>{checkValue(H10)}</td>
              <td className={qualificationStyle(checkValue(H11))}>{checkValue(H11)}</td>
              <td className={qualificationStyle(checkValue(H12))}>{checkValue(H12)}</td>
              <td className={qualificationStyle(checkValue(H13))}>{checkValue(H13)}</td>
              <td className={qualificationStyle(checkValue(H14))}>{checkValue(H14)}</td>
              <td className={qualificationStyle(checkValue(H15))}>{checkValue(H15)}</td>
              <td className={qualificationStyle(checkValue(H16))}>{checkValue(H16)}</td>
              <td className={qualificationStyle(checkValue(H17))}>{checkValue(H17)}</td>
              <td className={qualificationStyle(checkValue(H18))}>{checkValue(H18)}</td>
              <td className={qualificationStyle(checkValue(H19))}>{checkValue(H19)}</td>
              <td className={qualificationStyle(checkValue(H20))}>{checkValue(H20)}</td>        
              <td className={qualificationStyle(checkValue(H21))}>{checkValue(H21)}</td>
              <td className={qualificationStyle(checkValue(H22))}>{checkValue(H22)}</td>
              <td className={qualificationStyle(checkValue(H23))}>{checkValue(H23)}</td>
              <td className={qualificationStyle(checkValue(actual))}>{checkValue(actual)}</td>
            </tr>
        )
      }else{
        
        let { Data, Actual,ActualAcumulable,Previst,PrevistAcumulable,Comptador,Hores} = resultat 
        Hores =  resultat['Hores']
        filename = "Estat_transit_"+resultats[0]['Data']+'_'+resultats[resultats.length-1]['Data']+".csv"
        let {H01,P01,H02,P02,H03,P03,H04,P04,H05,P05,H06,P06,H07,P07,H08,P08,H09,P09,H10,P10,H11,P11,H12,P12,H13,P13,H14,P14,H15,P15,H16,P16,H17,P17,H18,P18,H19,P19,H20,P20,H21,P21,H22,P22,H23,P23,H24,P24} = Hores

        resultats_csv.push([Data,H24,H01,H02,H03,H04,H05,H06,H07,H08,H09,H10,H11,H12,H13,H14,H15,H16,H17,H18,H19,H20,H21,H22,H23,Actual])
        
        return (
            <tr key={Data}>
              <td className={'bg-info'}>{Data}</td>
              <td className={qualificationStyle(H24)}>{H24}</td>
              <td className={qualificationStyle(H01)}>{H01}</td>
              <td className={qualificationStyle(H02)}>{H02}</td>
              <td className={qualificationStyle(H03)}>{H03}</td>
              <td className={qualificationStyle(H04)}>{H04}</td>
              <td className={qualificationStyle(H05)}>{H05}</td>     
              <td className={qualificationStyle(H06)}>{H06}</td>         
              <td className={qualificationStyle(H07)}>{H07}</td>
              <td className={qualificationStyle(H08)}>{H08}</td>
              <td className={qualificationStyle(H09)}>{H09}</td>
              <td className={qualificationStyle(H10)}>{H10}</td>
              <td className={qualificationStyle(H11)}>{H11}</td>
              <td className={qualificationStyle(H12)}>{H12}</td>
              <td className={qualificationStyle(H13)}>{H13}</td>
              <td className={qualificationStyle(H14)}>{H14}</td>
              <td className={qualificationStyle(H15)}>{H15}</td>
              <td className={qualificationStyle(H16)}>{H16}</td>
              <td className={qualificationStyle(H17)}>{H17}</td>
              <td className={qualificationStyle(H18)}>{H18}</td>
              <td className={qualificationStyle(H19)}>{H19}</td>
              <td className={qualificationStyle(H20)}>{H20}</td>        
              <td className={qualificationStyle(H21)}>{H21}</td>
              <td className={qualificationStyle(H22)}>{H22}</td>
              <td className={qualificationStyle(H23)}>{H23}</td>
              <td className={qualificationStyle(Actual)}>{Actual}</td>
            </tr>
        )
      }
    })
    //console.log("title traffic table", title)
      return (
        <>
        {title}
        <div className={(!props.map || resultats.length<1)?"":"vertical-scroll"}>
          
          <Table id='transit' bordered hover responsive className="text-center" size="sm">
            <thead>
              <tr>{ headers.length>0?headers:<td></td> }</tr>
            </thead>
            <tbody>
              { table.length>0?table:<tr><td></td></tr> }
            </tbody>
          </Table>
        </div>
        <br/>
        {resultats.length<1?null:<CSVLink data={resultats_csv} headers={props.capçalera}  filename={filename} className="btn btn-outline-info btn-sm w-100">
                                    Descarrega csv
                                  </CSVLink>}
        
      </>
      );
}

const qualificationStyle = (qualification) => { 
  switch(qualification) {
    case 'Molt fluid':
      return 'bg-primary';
    case 'Fluid':
      return 'bg-success';
    case 'Dens':
      return 'bg-warning';
    case 'Molt dens':
      return 'bg-danger';
    case 'Congestionat':  
      return 'purple';
    case 'Tallat':  
      return 'bg-secondary';
    default:
      return 'bg-dark text-light';
  }
  
}

const checkValue = (valor) => { 
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
    
export default TrafficDataTable 