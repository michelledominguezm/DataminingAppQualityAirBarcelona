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

const TrafficHoursLineChart = (props) => { 
    let title = ''
    let dates = []
    let datasets = null
    if(props.resultats.length>0 && props.resultats[0]!=null){
     datasets = props.resultats.map((resultat, index) => {
      let estacio = ''
      let nom = ''
      let e = ''
      if(props.mostraestacions){
        
        let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
        title = Data
        Hores =  resultat['Hores']
        if(Hores==undefined){
          let { id, tram_id,any,mes,dia,H01,P01,H02,P02,H03,P03,H04,P04,H05,P05,H06,P06,H07,P07,H08,P08,H09,P09,H10,P10,H11,P11,H12,P12,H13,P13,H14,P14,H15,P15,H16,P16,H17,P17,H18,P18,H19,P19,H20,P20,H21,P21,H22,P22,H23,P23,H24,P24,actual,previst,complet} = resultat 
          estacio = 'Estat'
          return (    
            {
              label: estacio,
              borderColor: getRandomRgb(),
              steppedLine: false,
              data: [checkValue(H24),checkValue(H01),checkValue(H02),checkValue(H03),checkValue(H04),checkValue(H05),checkValue(H06),checkValue(H07),checkValue(H08),checkValue(H09),checkValue(H10),checkValue(H11),checkValue(H12),checkValue(H13),checkValue(H14),checkValue(H15),checkValue(H16),checkValue(H17),checkValue(H18),checkValue(H19),checkValue(H20),checkValue(H21),checkValue(H22),checkValue(H23)],
              fill:false,
              yAxisID: 'y-axis-1',
            })
        }else{
          let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
          estacio = 'Estat'
          return (    
            {
              label: estacio,
              borderColor: getRandomRgb(),
              steppedLine: false,
              data: [H24,H01,H02,H03,H04,H05,H06,H07,H08,H09,H10,H11,H12,H13,H14,H15,H16,H17,H18,H19,H20,H21,H22,H23],
              fill:false,
              yAxisID: 'y-axis-1',
            })
        }
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
            data: [H24,H01,H02,H03,H04,H05,H06,H07,H08,H09,H10,H11,H12,H13,H14,H15,H16,H17,H18,H19,H20,H21,H22,H23],
            fill:false,
            yAxisID: 'y-axis-1',
          })
      } 
  })
}

console.log("datasets linechart "+props.mostraestacions)
console.log(datasets)
const data= {
  type: 'line',
  xLabels: props.labels,
  yLabels: ['Tallat','Congestionat','Molt dens', 'Dens', 'Fluid','Molt fluid','Sense dades'],
  datasets: datasets
}
console.log("data linechart "+props.mostraestacions)
console.log(data)

  const options= {
    maintainAspectRatio: false,
    responsive: true,
    title: {
      display: props.mostratitol,
      text: "Resultats de transit "+ title 
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
              return "Estat del transit";
          },
            label: function(tooltipItem,data) {
              var label = data.datasets[tooltipItem.datasetIndex].label;
              return label+" "+tooltipItem.xLabel+": "+tooltipItem.yLabel;
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
          type: 'category',
          position: 'left',
          id: 'y-axis-1',
          display: true,
          scaleLabel: {
            display: false,
            labelString: 'Estat'
          },
          ticks: {
              callback: function(label, index, labels) {
                return label ;
              },
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

export default TrafficHoursLineChart 