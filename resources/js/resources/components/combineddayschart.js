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

const CombinedDaysChart = (props) => {
    let colors = ["rgb(255, 0, 0)",	"rgb(255, 128, 0)","rgb(255, 191, 0)",	"rgb(255, 255, 0)",	"rgb(191, 255, 0)",	"rgb(128, 255, 0)",	"rgb(0, 255, 128)",	"rgb(0, 255, 191)",	"rgb(0, 255, 255)",	"rgb(0, 191, 255)",	"rgb(0, 128, 255)",	"rgb(0, 64, 255)",		"rgb(128, 0, 255)",	"rgb(191, 0, 255)","rgb(255, 0, 255)",	"rgb(255, 0, 191)",	"rgb(255, 0, 128)","rgb(255, 0, 64)"] 
    let title = ''
    let dates = []
    let labels = []
    let data1,data2,data3 = null
    let datasets, datasets1, datasets2 = null
    if(props.congestions.length>0 && props.congestions[0]!=null){
      data1 = props.congestions.map((resultat, index) => {  
        let estacio = ''
        let { Data, Actual,ActualAcumulable,Qualificacio,Comptador,Hores} = resultat 
        Hores =  resultat['Hores']
        let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
        estacio =  Data;
        labels.push(estacio)
        dates.push(Data);
        return inverseCheckValue(Actual)
      })
    title = dates[0]+' - '+dates[dates.length-1]
    }
    datasets = 
      [{
        label: 'Estat del trànsit',
        backgroundColor:  (data1==null||data1.length>colors.length?getRandomRgb():colors[data1.length-1]),
        borderColor: 	"rgb(0,0,0)",
        data: data1,
        fill:true,
        yAxisID: 'y-axis-1',
        minBarLength: 5,
      }]

  //console.log("datasets linechart combi 1",datasets)
  if (datasets!=null && props.resultats.length>0){
    data2 = props.resultats.map((resultat, index) => {
      let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
      Hores =  resultat['Hores']
      let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
      return Mitjana
    })
    title = dates[0]+' - '+dates[dates.length-1]
    datasets1 =[    
      {
        label: 'Qualitat de l\'aire',
        borderColor: getRandomRgb(),
        steppedLine: false,
        data: data2,
        fill:false,
        yAxisID: 'y-axis-2',
        type: 'line',
      }]

    data3 = props.resultats.map((resultat, index) => {
      let { Data, Mitjana,MitjanaAcumulable,Qualificacio,Comptador,Hores} = resultat 
      Hores =  resultat['Hores']
      let {H01,R01,H02,R02,H03,R03,H04,R04,H05,R05,H06,R06,H07,R07,H08,R08,H09,R09,H10,R10,H11,R11,H12,R12,H13,R13,H14,R14,H15,R15,H16,R16,H17,R17,H18,R18,H19,R19,H20,R20,H21,R21,H22,R22,H23,R23,H24,R24} = Hores
      
      return Qualificacio
    })

    datasets2 = 
    {
      label: 'QualificacioQualitat',
      borderColor: 'rgba(255,0,0,0)',
      data: data3,
      fill:false,
      yAxisID: 'y-axis-3',
      pointRadius: 0,
      type: 'line',
    }
  //console.log("datasets linechart combi 1 true/false",datasets[0].data )
  if (datasets[0].data!=undefined){
    const data= {
      type: 'bar',  
      xLabels: dates,
      datasets:  datasets.concat(datasets1.concat(datasets2))
      }
      //console.log("data linechart combi",data)

    const options= {
      maintainAspectRatio: false,
      responsive: true,
      title: {
        display: props.mostratitol,
        text: "Resultats de contaminació i trànsit "+ title 
      },
      legend: {
        labels: {
          filter: function(item, chart) {
              return !item.text.includes('QualificacioQualitat');
          }
        }
      },
      spanGaps: true,
      tooltips: {
        mode: 'point',
        intersect: true,
        callbacks: {
              title: function(tooltipItems, data) {
                return "Resultats";
            },
              label: function(tooltipItem,data) {
                var value = data.datasets[tooltipItem.datasetIndex].data[0];
                var label = data.datasets[tooltipItem.datasetIndex].label;    
                if (!isNaN(tooltipItem.yLabel) && value!==null){
                  if(label=="Estat del trànsit"){
                    return label+" "+tooltipItem.xLabel+": "+checkValue(tooltipItem.yLabel,false);
                  }else{
                    return label+": "+tooltipItem.yLabel.toFixed(2) +" "+ props.info_contaminant['unitats']+", estat: "+ checker(tooltipItem.yLabel.toFixed(2),props.info_indicador,true);
                  }
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
            position: 'right',
            id: 'y-axis-1',
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Estat del trànsit'
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
          {
            type: 'linear',
            position: 'left',
            id: 'y-axis-2',
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Mesures contaminació '+(props.info_contaminant==null?'':'('+props.info_contaminant['unitats']+')')
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
              id: 'y-axis-3',
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
          < Bar
            data={data}
            height={props.height}
            width={350}
            options={options}
        />
        </div>
    )
  }
}
  return null
  
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

export default CombinedDaysChart 