import React, { Component } from 'react'
import { Table } from 'react-bootstrap';
import TableLegend from './tablelegend';
import { CSVLink } from "react-csv";
import checker from './checker';

const ContaminationDataTable = (props) => { 
    let headers = ''
    let nom_indicador = ''
    let resultats = props.resultats
    let capçalera = props.capçalera
    let resultats_csv = []
    let data = '[sense especificar]';
    let contaminant = '[sense especificar]'
    let indicador = '[sense especificar]'
    let title = ''
    let filename = ''
   
  if (resultats.length>0){
    let complet = resultats[0]["complet"]
    let valid = resultats[0]["valid"]
    contaminant = resultats[0]["contaminant_id"]
    indicador = resultats[0]["indicador_id"]
    data = resultats[0]["dia"]+'/'+resultats[0]["mes"]+'/'+resultats[0]["any"];

    headers = Object.keys(resultats[0])
    
    if(props.info_contaminant!=null && props.info_indicador!=null){
      nom_indicador = props.info_indicador['nom']
      title = (props.map==false)?<p className={'font-weight-bold pt-3'}>Mostrant els resultats mesurats en el periode especificat pel contaminant {props.info_contaminant['simbol']} amb l'indicador {nom_indicador}</p>:<p className={'font-weight-bold pt-3'}>Mostrant els resultats mesurats el {data} pel contaminant {props.info_contaminant['simbol']} amb l'indicador {nom_indicador}</p> 
      title = (props.mostratitol==true)?title:null
    }

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
   let estacio,e,nom,nom_csv = ''
   
   let table =  resultats.map((resultat, index) => {
      if(props.map){
        let { id, estacio_id,contaminant_id,indicador_id,mostra_id,immissio_id,any,mes,dia,H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24,maxim,minim,mitjana,qualificacio,complet,valid} = resultat 
      
        if(capçalera.length==28 && props.info_contaminant!=null){
          estacio = null;
          e = props.estacions.filter(function(res) {return res['id']==estacio_id})
          nom_csv = e.length==0?'nom':e[0]['nom_estacio']
          nom_csv+='_'
          resultats_csv.push([H01+props.info_contaminant['unitats']+' - '+checkValue(R01,nom_indicador),H02+props.info_contaminant['unitats']+' - '+checkValue(R02,nom_indicador),H03+props.info_contaminant['unitats']+' - '+checkValue(R03,nom_indicador),H04+props.info_contaminant['unitats']+' - '+checkValue(R04,nom_indicador),H05+props.info_contaminant['unitats']+' - '+checkValue(R05,nom_indicador),H06+props.info_contaminant['unitats']+' - '+checkValue(R06,nom_indicador),H07+props.info_contaminant['unitats']+' - '+checkValue(R07,nom_indicador),H08+props.info_contaminant['unitats']+' - '+checkValue(R08,nom_indicador),H09+props.info_contaminant['unitats']+' - '+checkValue(R09,nom_indicador),H10+props.info_contaminant['unitats']+' - '+checkValue(R10,nom_indicador),H11+props.info_contaminant['unitats']+' - '+checkValue(R11,nom_indicador),H12+props.info_contaminant['unitats']+' - '+checkValue(R12,nom_indicador),H13+props.info_contaminant['unitats']+' - '+checkValue(R13,nom_indicador),H14+props.info_contaminant['unitats']+' - '+checkValue(R14,nom_indicador),H15+props.info_contaminant['unitats']+' - '+checkValue(R15,nom_indicador),H16+props.info_contaminant['unitats']+' - '+checkValue(R16,nom_indicador),H17+props.info_contaminant['unitats']+' - '+checkValue(R17,nom_indicador),H18+props.info_contaminant['unitats']+' - '+checkValue(R18,nom_indicador),H19+props.info_contaminant['unitats']+' - '+checkValue(R19,nom_indicador),H20+props.info_contaminant['unitats']+' - '+checkValue(R20,nom_indicador),H21+props.info_contaminant['unitats']+' - '+checkValue(R21,nom_indicador),H22+props.info_contaminant['unitats']+' - '+checkValue(R22,nom_indicador),H23+props.info_contaminant['unitats']+' - '+checkValue(R23,nom_indicador),H24+props.info_contaminant['unitats']+' - '+checkValue(R24,nom_indicador),
          maxim.split("H")[1]+maxim,minim.split("H")[1]+minim,maxim==""?'':mitjana+props.info_contaminant['unitats'],checkValue(qualificacio,nom_indicador)])
          
        }else{
          e = props.estacions.filter(function(res) {return res['id']==estacio_id})
          nom = e.length==0?'nom':e[0]['nom_estacio']
          estacio = <td className={'bg-info'}>{nom.split('- ')[1]}</td>
          if (props.info_contaminant!=null){
           resultats_csv.push([nom,H01+props.info_contaminant['unitats']+' - '+checkValue(R01,nom_indicador),H02+props.info_contaminant['unitats']+' - '+checkValue(R02,nom_indicador),H03+props.info_contaminant['unitats']+' - '+checkValue(R03,nom_indicador),H04+props.info_contaminant['unitats']+' - '+checkValue(R04,nom_indicador),H05+props.info_contaminant['unitats']+' - '+checkValue(R05,nom_indicador),H06+props.info_contaminant['unitats']+' - '+checkValue(R06,nom_indicador),H07+props.info_contaminant['unitats']+' - '+checkValue(R07,nom_indicador),H08+props.info_contaminant['unitats']+' - '+checkValue(R08,nom_indicador),H09+props.info_contaminant['unitats']+' - '+checkValue(R09,nom_indicador),H10+props.info_contaminant['unitats']+' - '+checkValue(R10,nom_indicador),H11+props.info_contaminant['unitats']+' - '+checkValue(R11,nom_indicador),H12+props.info_contaminant['unitats']+' - '+checkValue(R12,nom_indicador),H13+props.info_contaminant['unitats']+' - '+checkValue(R13,nom_indicador),H14+props.info_contaminant['unitats']+' - '+checkValue(R14,nom_indicador),H15+props.info_contaminant['unitats']+' - '+checkValue(R15,nom_indicador),H16+props.info_contaminant['unitats']+' - '+checkValue(R16,nom_indicador),H17+props.info_contaminant['unitats']+' - '+checkValue(R17,nom_indicador),H18+props.info_contaminant['unitats']+' - '+checkValue(R18,nom_indicador),H19+props.info_contaminant['unitats']+' - '+checkValue(R19,nom_indicador),H20+props.info_contaminant['unitats']+' - '+checkValue(R20,nom_indicador),H21+props.info_contaminant['unitats']+' - '+checkValue(R21,nom_indicador),H22+props.info_contaminant['unitats']+' - '+checkValue(R22,nom_indicador),H23+props.info_contaminant['unitats']+' - '+checkValue(R23,nom_indicador),H24+props.info_contaminant['unitats']+' - '+checkValue(R24,nom_indicador),
            maxim.split("H")[1]+maxim[0],minim.split("H")[1]+minim[0],maxim==""?'':mitjana+props.info_contaminant['unitats'],checkValue(qualificacio,nom_indicador)])
          }
        }
        filename = nom_indicador+"_"+(props.info_contaminant==null?'':props.info_contaminant['simbol'])+"_"+nom_csv+data+".csv"
        //let max = maxim
        if (maxim==""&&minim!=""){
          maxim=minim
        }
        return (
          <tr key={id}>
            {estacio}
            <td className={qualificationStyle({R01},nom_indicador,true)}>{H01}</td>
            <td className={qualificationStyle({R02},nom_indicador,true)}>{H02}</td>
            <td className={qualificationStyle({R03},nom_indicador,true)}>{H03}</td>  
            <td className={qualificationStyle({R04},nom_indicador,true)}>{H04}</td>      
            <td className={qualificationStyle({R05},nom_indicador,true)}>{H05}</td>     
            <td className={qualificationStyle({R06},nom_indicador,true)}>{H06}</td>           
            <td className={qualificationStyle({R07},nom_indicador,true)}>{H07}</td>
            <td className={qualificationStyle({R08},nom_indicador,true)}>{H08}</td>
            <td className={qualificationStyle({R09},nom_indicador,true)}>{H09}</td>
            <td className={qualificationStyle({R10},nom_indicador,true)}>{H10}</td>
            <td className={qualificationStyle({R11},nom_indicador,true)}>{H11}</td>
            <td className={qualificationStyle({R12},nom_indicador,true)}>{H12}</td>
            <td className={qualificationStyle({R13},nom_indicador,true)}>{H13}</td>
            <td className={qualificationStyle({R14},nom_indicador,true)}>{H14}</td>
            <td className={qualificationStyle({R15},nom_indicador,true)}>{H15}</td>
            <td className={qualificationStyle({R16},nom_indicador,true)}>{H16}</td>
            <td className={qualificationStyle({R17},nom_indicador,true)}>{H17}</td>
            <td className={qualificationStyle({R18},nom_indicador,true)}>{H18}</td>
            <td className={qualificationStyle({R19},nom_indicador,true)}>{H19}</td>
            <td className={qualificationStyle({R20},nom_indicador,true)}>{H20}</td>           
            <td className={qualificationStyle({R21},nom_indicador,true)}>{H21}</td>
            <td className={qualificationStyle({R22},nom_indicador,true)}>{H22}</td>
            <td className={qualificationStyle({R23},nom_indicador,true)}>{H23}</td>
            <td className={qualificationStyle({R24},nom_indicador,true)}>{H24}</td>
            <td className={maxim==""?'bg-dark':'bg-info'}>{maxim==""?"":maxim.split("H")[1]+maxim[0]}</td>
            <td className={minim==""?'bg-dark':'bg-info'}>{minim==""?"":minim.split("H")[1]+minim[0]}</td>
            <td className={qualificationStyle(maxim==""&&minim==""?null:{qualificacio},nom_indicador,true)}>{maxim==""&&minim==""?null:mitjana.toFixed(2)}</td>
            <td className={qualificationStyle(maxim==""&&minim==""?null:{qualificacio},nom_indicador,true)}>{checkValue(maxim==""&&minim==""?null:qualificacio,nom_indicador)}</td>
          </tr>
        )
      }else{
        let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
        Hores =  resultat['Hores']
        filename = nom_indicador+"_"+(props.info_contaminant==null?'':props.info_contaminant['simbol'])+"_"+resultats[0]['Data']+'_'+resultats[resultats.length-1]['Data']+".csv"
        let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
        if (props.info_contaminant!=null){
        resultats_csv.push([Data,H01==null?'':H01.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R01,nom_indicador),
            H02==null?'':H02.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R02,nom_indicador),
            H03==null?'': H03.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R03,nom_indicador),
            H04==null?'': H04.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R04,nom_indicador),
            H05==null?'': H05.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R05,nom_indicador),
            H06==null?'': H06.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R06,nom_indicador),
            H07==null?'': H07.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R07,nom_indicador),
            H08==null?'': H08.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R08,nom_indicador),
            H09==null?'': H09.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R09,nom_indicador),
            H10==null?'': H10.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R10,nom_indicador),
            H11==null?'': H11.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R11,nom_indicador),
            H12==null?'': H12.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R12,nom_indicador),
            H13==null?'': H13.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R13,nom_indicador),
            H14==null?'': H14.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R14,nom_indicador),
            H15==null?'': H15.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R15,nom_indicador),
            H16==null?'': H16.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R16,nom_indicador),
            H17==null?'': H17.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R17,nom_indicador),
            H18==null?'': H18.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R18,nom_indicador),
            H19==null?'': H19.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R19,nom_indicador),
            H20==null?'': H20.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R20,nom_indicador),
            H21==null?'': H21.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R21,nom_indicador),
            H22==null?'': H22.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R22,nom_indicador),
            H23==null?'': H23.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R23,nom_indicador),
            H24==null?'': H24.toFixed(2)+props.info_contaminant['unitats']+' - '+checkValue(R24,nom_indicador),
            Mitjana==null?'':Mitjana+props.info_contaminant['unitats'],checkValue(Qualificacio,nom_indicador)])
        } 
        return (
          <tr key={Data}>
            <td className={'bg-info'}>{Data}</td>
            <td className={qualificationStyle(checker(H01,props.info_indicador,true),nom_indicador,false)}>{H01==null?null:H01.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H02,props.info_indicador,true),nom_indicador,false)}>{H02==null?null:H02.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H03,props.info_indicador,true),nom_indicador,false)}>{H03==null?null:H03.toFixed(2)}</td>  
            <td className={qualificationStyle(checker(H04,props.info_indicador,true),nom_indicador,false)}>{H05==null?null:H04.toFixed(2)}</td>      
            <td className={qualificationStyle(checker(H05,props.info_indicador,true),nom_indicador,false)}>{H05==null?null:H05.toFixed(2)}</td>     
            <td className={qualificationStyle(checker(H06,props.info_indicador,true),nom_indicador,false)}>{H06==null?null:H06.toFixed(2)}</td>           
            <td className={qualificationStyle(checker(H07,props.info_indicador,true),nom_indicador,false)}>{H07==null?null:H07.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H08,props.info_indicador,true),nom_indicador,false)}>{H08==null?null:H08.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H09,props.info_indicador,true),nom_indicador,false)}>{H09==null?null:H09.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H10,props.info_indicador,true),nom_indicador,false)}>{H10==null?null:H10.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H11,props.info_indicador,true),nom_indicador,false)}>{H11==null?null:H11.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H12,props.info_indicador,true),nom_indicador,false)}>{H12==null?null:H12.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H13,props.info_indicador,true),nom_indicador,false)}>{H13==null?null:H13.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H14,props.info_indicador,true),nom_indicador,false)}>{H14==null?null:H14.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H15,props.info_indicador,true),nom_indicador,false)}>{H15==null?null:H15.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H16,props.info_indicador,true),nom_indicador,false)}>{H16==null?null:H16.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H17,props.info_indicador,true),nom_indicador,false)}>{H17==null?null:H17.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H18,props.info_indicador,true),nom_indicador,false)}>{H18==null?null:H18.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H19,props.info_indicador,true),nom_indicador,false)}>{H19==null?null:H19.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H20,props.info_indicador,true),nom_indicador,false)}>{H20==null?null:H20.toFixed(2)}</td>           
            <td className={qualificationStyle(checker(H21,props.info_indicador,true),nom_indicador,false)}>{H21==null?null:H21.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H22,props.info_indicador,true),nom_indicador,false)}>{H22==null?null:H22.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H23,props.info_indicador,true),nom_indicador,false)}>{H23==null?null:H23.toFixed(2)}</td>
            <td className={qualificationStyle(checker(H24,props.info_indicador,true),nom_indicador,false)}>{H24==null?null:H24.toFixed(2)}</td>
            
            <td className={qualificationStyle(Comptador==0?null:checker(Mitjana.toFixed(2),props.info_indicador,true),nom_indicador,false)}>{Mitjana==null?null:Mitjana.toFixed(2)}</td>
            <td className={qualificationStyle(Comptador==0?null:checker(Mitjana.toFixed(2),props.info_indicador,true),nom_indicador,false)}></td>
          </tr>
        )
      }
    })
    return (
      <div>
        {title}
        <TableLegend 
            info_indicador = {props.info_indicador}
            info_contaminant = {props.info_contaminant}
            mostrallegenda = {props.mostrallegenda}
        />
        <Table id='resultats'  bordered hover responsive className="text-center" size="sm">
          <thead>
            <tr>{ headers.length>0?headers:<td></td> }</tr>
          </thead>
          <tbody>
            { table.length>0?table:<tr><td></td></tr> }
          </tbody>
        </Table>
        
        <CSVLink data={resultats_csv} headers={props.capçalera}  filename={filename} className="btn btn-outline-info btn-sm w-100">
          Descarrega csv
        </CSVLink>
        
      </div>
    );
  }

const qualificationStyle = (qualification, indicador, check) => { 
  if(qualification == null) {
    return 'bg-dark';
  }
  if(check){
      if(indicador == 'IQAM'){
        switch(Object.values(qualification)[0]) {
          case 'bo':
            return 'bg-primary';
          case 'moderat':
            return 'bg-success';
          case 'regular':
            return 'bg-warning';
          case 'dolent':
            return 'bg-danger';
          case 'molt_dolent':
          case 'molt dolent':  
            return 'purple';
          default:
            return 'bg-dark';
        }
      }else{
        switch(Object.values(qualification)[0]) {
          case 'bo':
            return 'bg-success';
          case 'moderat':
            return 'bg-warning';
          case 'regular':
            return 'bg-danger';
          case 'dolent':
            return 'purple';
          case 'molt_dolent':
          case 'molt dolent': 
            return 'purple';
          default:
            return 'bg-dark';
        }
      }
  }else{
      if(indicador == 'IQAM'){
        switch(qualification) {
          case 'bo':
            return 'bg-primary';
          case 'moderat':
            return 'bg-success';
          case 'regular':
            return 'bg-warning';
          case 'dolent':
            return 'bg-danger';
          case 'molt_dolent':
          case 'molt dolent':  
            return 'purple';
          default:
            return 'bg-dark';
        }
      }else{
        switch(qualification) {
          case 'bo':
            return 'bg-success';
          case 'regular':
            return 'bg-warning';
          case 'dolent':
            return 'bg-danger';
          case 'molt dolent':
          case 'molt dolent': 
            return 'purple';
          default:
            return 'bg-dark';
        }
      }
  }
}

const checkValue = (qualification, indicador) => { 

  if(indicador == 'ICQA'){
    switch(qualification) {
      case 'bo':
        return 'bo';
      case 'moderat':
        return 'regular';
      case 'regular':
        return 'dolent';
      case 'dolent':
        return 'molt_dolent';
      case 'molt_dolent':
        return 'molt_dolent';
      default:
        return null;
    }
  }
  return qualification
    
}
export default ContaminationDataTable 