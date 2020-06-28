import React, { Component } from 'react';
import {Line, Bar} from 'react-chartjs-2';
import checker from './checker';

function getRandomRgb() {
  var num = Math.round(0xffffff * Math.random());
  var r = num >> 16;
  var g = num >> 8 & 255;
  var b = num & 255;
  return 'rgb(' + r + ', ' + g + ', ' + b + ')';
}

const ContaminationHoursLineChart = (props) => { 
    let title = ''
    let dates = []
    let datasets = props.resultats.map((resultat, index) => {
      let estacio = ''
      let nom = ''
      let e = ''
      
      if(props.mostraestacions){
        title = (props.resultats[0]==null?'':(props.resultats[0]["dia"]+'/'+props.resultats[0]["mes"]+'/'+props.resultats[0]["any"]))
        let { id, estacio_id,contaminant_id,indicador_id,mostra_id,immissio_id,any,mes,dia,H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24,maxim,minim,mitjana,qualificacio,complet,valid} = resultat 
        e = props.estacions.filter(function(res) {return res['id']==estacio_id})
        nom = ''+(e.length==0?String.valueOf(estacio_id):e[0]['nom_estacio'])
        estacio = nom.split("-")[1]
        return (    
          {
            label: estacio,
            borderColor: getRandomRgb(),
            steppedLine: false,
            data: [H01,H02,H03,H04,H05,H06,H07,H08,H09,H10,H11,H12,H13,H14,H15,H16,H17,H18,H19,H20,H21,H22,H23,H24],
            fill:false,
            yAxisID: 'y-axis-1',
          })
      }else{
        let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
        Hores =  resultat['Hores']
        let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
        estacio =  Data;
        e = null;
        
        dates.push(Data);
        title = dates[0]+' - '+dates[dates.length-1]

        return (    
          {
            label: estacio,
            borderColor: getRandomRgb(),
            steppedLine: false,
            data: [H01,H02,H03,H04,H05,H06,H07,H08,H09,H10,H11,H12,H13,H14,H15,H16,H17,H18,H19,H20,H21,H22,H23,H24],
            fill:false,
            yAxisID: 'y-axis-1',
          }
        )
      } 
  })
  let datasets2 = props.resultats.map((resultat, index) => {
    let estacio = ''
    let nom = ''
    let e = ''
    if(props.mostraestacions){
      let { id, estacio_id,contaminant_id,indicador_id,mostra_id,immissio_id,any,mes,dia,H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24,maxim,minim,mitjana,qualificacio,complet,valid} = resultat 
       e = props.estacions.filter(function(res) {return res['id']==estacio_id})
       nom = ''+(e.length==0?String.valueOf(estacio_id):e[0]['nom_estacio'])
       estacio = "Qualitat"+ nom.split("-")[1]
       return (
        {
          label: estacio,
          borderColor: 'rgba(255,0,0,0)',
          data: [R01,R02,R03,R04,R05,R06,R07,R08,R09,R10,R11,R12,R13,R14,R15,R16,R17,R18,R19,R20,R21,R22,R23,R24],
          fill:false,
          yAxisID: 'y-axis-2',
          pointRadius: 0,
        }
      )
    }else{
      let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
      Hores =  resultat['Hores']
      let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
      estacio = "Qualitat "+ Data;
      e = null;
      return (
        {
          label: estacio,
          borderColor: 'rgba(255,0,0,0)',
          data: [checker(H01,props.info_indicador,true),checker(H02,props.info_indicador,true),checker(H03,props.info_indicador,true),checker(H04,props.info_indicador,true),checker(H05,props.info_indicador,true),checker(H06,props.info_indicador,true),checker(H07,props.info_indicador,true),checker(H08,props.info_indicador,true),checker(H09,props.info_indicador,true),checker(H10,props.info_indicador,true),checker(H11,props.info_indicador,true),checker(H12,props.info_indicador,true),checker(H13,props.info_indicador,true),checker(H14,props.info_indicador,true),checker(H15,props.info_indicador,true),checker(H16,props.info_indicador,true),checker(H17,props.info_indicador,true),checker(H18,props.info_indicador,true),checker(H19,props.info_indicador,true),checker(H20,props.info_indicador,true),checker(H21,props.info_indicador,true),checker(H22,props.info_indicador,true),checker(H23,props.info_indicador,true),checker(H24,props.info_indicador,true)],
          fill:false,
          yAxisID: 'y-axis-2',
          pointRadius: 0,
        }
      )
    }
  })
  //console.log("datasets linechart "+props.mostraestacions)
  //console.log(datasets)
  const data= {
    type: 'line',
    xLabels: props.labels,
    yLabels: ['molt dolent', 'dolent', 'regular','moderat','bo',''],
    datasets: datasets.concat(datasets2)
  }
  //console.log("data linechart "+props.mostraestacions)
  //console.log(data)

  const options= {
    maintainAspectRatio: false,
    responsive: true,
    title: {
      display: props.mostratitol,
      text: (props.info_contaminant==null)?'':"Resultats "+ title +" pel contaminant "+props.info_contaminant['simbol']+ (props.info_indicador==null?'':" "+" ("+props.info_indicador['nom']+")")
    },
    legend: {
      labels: {
        filter: function(item, chart) {
            return !item.text.includes('Qualitat');
        }
      }
    },
    spanGaps: true,
    tooltips: {
      mode: 'point',
      intersect: true,
      callbacks: {
            title: function(tooltipItems, data) {
              return "Resultats: mesura i qualificació";
          },
            label: function(tooltipItem,data) {
              var value = data.datasets[tooltipItem.datasetIndex].data[0];
              var label = data.datasets[tooltipItem.datasetIndex].label;
              let qualif = props.resultats[tooltipItem.datasetIndex]===undefined?'':props.resultats[tooltipItem.datasetIndex]['R'+tooltipItem.xLabel[0]+tooltipItem.xLabel[1]]
              if(!props.mostraestacions){
                qualif = props.resultats[tooltipItem.datasetIndex]===undefined?'':props.resultats[tooltipItem.datasetIndex]['Hores']['R'+tooltipItem.xLabel[0]+tooltipItem.xLabel[1]]
              }
             
              if (!isNaN(tooltipItem.yLabel) && value!==null){
                return label+": "+tooltipItem.yLabel.toFixed(2) +" "+ props.info_contaminant['unitats']+", estat: "+ checker(tooltipItem.yLabel.toFixed(2),props.info_indicador,true);
              }else{
                return null
              }
            }
        }
    },
    scales: {
      xAxes: [
        {
          ticks: {
              callback: function(label, index, labels) {
                return label;
              }
          },
            display:true,
            scaleLabel: {
              display: true,
              labelString: 'Hora de la mesura'
            }
        }
      ],
      yAxes: [
        {
          type: 'linear',
          position: 'left',
          id: 'y-axis-1',
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Mesures '+(props.info_contaminant==null?'':'('+props.info_contaminant['unitats']+')')
          },
          ticks: {
            min: 0,
            stepSize: 1,
              callback: function(label, index, labels) {
                return label ;
              },
          },
        },
        {
          type: 'category',
          display: false,
          position: 'right',
          id: 'y-axis-2',
          scaleLabel: {
            display: true, 
            labelString: 'Qualificació'
          },
          ticks: {
            display:true 
            
            },
          gridLines: {
            drawOnChartArea: false, 
          },
        },    
        ]
      }
    }
    return(
        <div>
          < Line
            data={data}
            options={options}
            height={props.height}
        />
        </div>
    )
  
}

export default ContaminationHoursLineChart 