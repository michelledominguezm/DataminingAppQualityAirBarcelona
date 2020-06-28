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

const TrafficHoursBarChart = (props) => {
    let colors = ["rgb(255, 0, 0)",	"rgb(255, 128, 0)","rgb(255, 191, 0)",	"rgb(255, 255, 0)",	 "rgb(191, 255, 0)",	"rgb(128, 255, 0)",	 "rgb(0, 255, 128)",	"rgb(0, 255, 191)",	" rgb(0, 255, 255)",	 "rgb(0, 191, 255)",	 "rgb(0, 128, 255)",	"rgb(0, 64, 255)",		"rgb(128, 0, 255)",	  "rgb(191, 0, 255)", "rgb(255, 0, 255)",	"rgb(255, 0, 191)",	 "rgb(255, 0, 128)", "rgb(255, 0, 64)"] 
    let title = ''
    let dates = []
    let labels = []
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
          labels.push(estacio)
          return (    
            {
              label: estacio,
              backgroundColor: (index>colors.length?getRandomRgb():colors[Math.floor(Math.random() * colors.length)]),
              borderColor: 	"rgb(0,0,0)",
              data: [checkValue(H24,true),checkValue(H01,true),checkValue(H02,true),checkValue(H03,true),checkValue(H04,true),checkValue(H05,true),checkValue(H06,true),checkValue(H07,true),checkValue(H08,true),checkValue(H09,true),checkValue(H10,true),checkValue(H11,true),checkValue(H12,true),checkValue(H13,true),checkValue(H14,true),checkValue(H15,true),checkValue(H16,true),checkValue(H17,true),checkValue(H18,true),checkValue(H19,true),checkValue(H20,true),checkValue(H21,true),checkValue(H22,true),checkValue(H23,true)],
              fill:true,
              yAxisID: 'y-axis-1',
              minBarLength: 5,
            })
        }else{
          let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
          estacio = 'Estat'
          labels.push(estacio)
          return (    
            {
              label: estacio,
              backgroundColor: (index>colors.length?getRandomRgb():colors[index]),
              borderColor: 	"rgb(0,0,0)",
              data: [inverseCheckValue(H24),inverseCheckValue(H01),inverseCheckValue(H02),inverseCheckValue(H03),inverseCheckValue(H04),inverseCheckValue(H05),inverseCheckValue(H06),inverseCheckValue(H07),inverseCheckValue(H08),inverseCheckValue(H09),inverseCheckValue(H10),inverseCheckValue(H11),inverseCheckValue(H12),inverseCheckValue(H13),inverseCheckValue(H14),inverseCheckValue(H15),inverseCheckValue(H16),inverseCheckValue(H17),inverseCheckValue(H18),inverseCheckValue(H19),inverseCheckValue(H20),inverseCheckValue(H21),inverseCheckValue(H22),inverseCheckValue(H23)],
              fill:true,
              yAxisID: 'y-axis-1',
              minBarLength: 5,
            })
        }
        
      }else{
        let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
        Hores =  resultat['Hores']
        let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
        estacio =  Data;
        labels.push(estacio)
        e = null;
        dates.push(Data);
        title = dates[0]+' - '+dates[dates.length-1]
        return (    
          {
            label: estacio,
            backgroundColor:  (index>colors.length?getRandomRgb():colors[index]),
            borderColor: 	"rgb(0,0,0)",
            data: [inverseCheckValue(H24),inverseCheckValue(H01),inverseCheckValue(H02),inverseCheckValue(H03),inverseCheckValue(H04),inverseCheckValue(H05),inverseCheckValue(H06),inverseCheckValue(H07),inverseCheckValue(H08),inverseCheckValue(H09),inverseCheckValue(H10),inverseCheckValue(H11),inverseCheckValue(H12),inverseCheckValue(H13),inverseCheckValue(H14),inverseCheckValue(H15),inverseCheckValue(H16),inverseCheckValue(H17),inverseCheckValue(H18),inverseCheckValue(H19),inverseCheckValue(H20),inverseCheckValue(H21),inverseCheckValue(H22),inverseCheckValue(H23)],
            fill:true,
            yAxisID: 'y-axis-1',
            minBarLength: 5,
          })
        
      } 
  })
}

//console.log("datasets linechart "+props.mostraestacions)
//console.log(datasets)
const data= {
  type: 'bar',  
  xLabels: props.labels,
  labels: labels,
  datasets: datasets
}
//console.log("data linechart "+props.mostraestacions)
//console.log(data)

  const options= {
    maintainAspectRatio: false,
    responsive: true,
    title: {
      display: props.mostratitol,
      text: "Resultats de trànsit "+ title 
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
              return "Estat del trànsit";
          },
            label: function(tooltipItem,data) {
              var label = data.datasets[tooltipItem.datasetIndex].label;
             
              return label+" "+tooltipItem.xLabel+": "+checkValue(tooltipItem.yLabel,false);
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
          position: 'left',
          id: 'y-axis-1',
          display: true,
          scaleLabel: {
            display: false, 
            labelString: 'Estat'
          },
          ticks: {
            min: 0,
            max: 6,
            stepSize: 1,
            callback: function(label, index, labels) {
              return checkValue(label)
            },
          },
        },
          
        ]
      }
    }
    return(
        <div>
          < Bar
            data={data}
            height={props.height}
            width={350}
            options={options}
        />
        </div>
    )
}

const checkValue = (valor, returnInverse) => { 
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
  if(returnInverse){
    return inverseCheckValue(valor)
  }else{
    return valor
  }
  
}

const inverseCheckValue = (valor) => { 
  if(valor=="Sense dades"){
    valor = 0;
  }else if(valor == "Molt fluid"){
    valor = 1;
  }else if(valor == "Fluid"){
    valor = 2;
  }else if(valor == "Dens"){
    valor = 3;
  }else if(valor == "Molt dens"){
    valor = 4;
  }
  else if(valor=="Congestionat"){
    valor = 5;
  }
  else if(valor=="Tallat"){
    valor = 6;
  }else{
    valor = "Sense dades";
  }
  return valor
}

export default TrafficHoursBarChart 