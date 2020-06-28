import React, { Component } from 'react'
import { Table } from 'react-bootstrap';

const TableLegend = (props) => { 
  
  let info_indicador = props.info_indicador
  let info_contaminant = props.info_contaminant
  let headers = ''
  let table = ''

  if(info_indicador != undefined && info_indicador != null && info_contaminant != undefined && info_contaminant != null){
    
    headers = Object.keys(info_indicador)
    
    delete headers[headers.indexOf('id')];
    delete headers[headers.indexOf('contaminant_id')];
    delete headers[headers.indexOf('nom')];
    delete headers[headers.indexOf('llindar')];
    
    if(props.info_indicador['nom']=='ICQA'){
      delete headers[headers.indexOf('molt_dolent')];
      headers[headers.indexOf('moderat')] = 'REGULAR'
      headers[headers.indexOf('regular')] = 'DOLENT'
      headers[headers.indexOf('dolent')] = 'MOLT DOLENT'
    }
    headers[headers.indexOf('molt_dolent')] = 'MOLT DOLENT'
    
    headers = headers.map((key, index) => {
      return <th key={index} className={'bg-light'}>{key.toUpperCase()} </th>
    })
  
    table = props.info_indicador['nom']=='ICQA'?
        <tr key={info_indicador['id']}>
                    
            <td className={'bg-success'}>{info_indicador['bo'].split("[")[0]} {props.info_contaminant['unitats']}</td>
            <td className={'bg-warning'}>{info_indicador['moderat'].split("[")[0]} {props.info_contaminant['unitats']}</td>
            <td className={'bg-danger'}>{info_indicador['regular'].split("[")[0]} {props.info_contaminant['unitats']}</td>
            <td className={'purple'}>{info_indicador['dolent'].split("[")[0]} {props.info_contaminant['unitats']}</td>
            
        </tr>
        :
        <tr key={info_indicador['id']}>
            
            <td className={'bg-primary'}>{info_indicador['bo']} {props.info_contaminant['unitats']}</td>
            <td className={'bg-success'}>{info_indicador['moderat']} {props.info_contaminant['unitats']}</td>
            <td className={'bg-warning'}>{info_indicador['regular']} {props.info_contaminant['unitats']}</td>
            <td className={'bg-danger'}>{info_indicador['dolent']} {props.info_contaminant['unitats']}</td>
            <td className={'purple'}>{info_indicador['molt_dolent']} {props.info_contaminant['unitats']}</td>
        </tr>
  }
    return (
      <div>{props.mostrallegenda? 
        <Table id='legend' bordered responsive  className=" text-center" size="sm" >
          <thead>
            <tr>{ headers.length>0?headers:null }</tr>
          </thead>
          <tbody>
            { table!=''?table:null }
          </tbody>
        </Table>
      : null}
        
      </div>
    );
}


export default TableLegend

