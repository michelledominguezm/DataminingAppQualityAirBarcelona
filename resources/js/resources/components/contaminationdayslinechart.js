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

const ContaminationDaysLineChart = (props) => { 
    let title = ''
    let dates = []
    let data1 = props.resultats.map((resultat, index) => {
      let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
      Hores =  resultat['Hores']
      let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
    
      dates.push(Data);
      
      return Mitjana
   })
    title = dates[0]+' - '+dates[dates.length-1]
    let datasets =[    
      {
        label: 'Qualitat de l\'aire',
        borderColor: getRandomRgb(),
        steppedLine: false,
        data: data1,
        fill:false,
        yAxisID: 'y-axis-1',
      }]

    let data2 = props.resultats.map((resultat, index) => {
      let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
      Hores =  resultat['Hores']
      let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
      
      return Qualificacio
    })

    let datasets2 = {
      label: 'Qualificacio',
      borderColor: 'rgba(255,0,0,0)',
      data: data2,
      fill:false,
      yAxisID: 'y-axis-2',
      pointRadius: 0,
    }

    //console.log("datasets linechart")
    //console.log(datasets)
    const data= {
      type: 'line',
      xLabels: dates,
      yLabels: ['molt dolent', 'dolent', 'regular','moderat','bo',''],
      datasets: datasets.concat(datasets2)
    }
    //console.log("data linechart ")
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
            return !item.text.includes('Qualificacio');
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
              labelString: 'Dia de la mesura'
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

export default ContaminationDaysLineChart 