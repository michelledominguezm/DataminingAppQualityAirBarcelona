import React from 'react';

const Checker = (valor, indicador, calcula) => { 
  let qualificacio =  null
  let bo = null;
  let moderat = null;
  let regular = null;
  let dolent = null;
  let molt_dolent = null;

  if(indicador!=null){
    if(calcula){
      bo = indicador['bo'];
      moderat = indicador['moderat'];
      regular = indicador['regular'];
      dolent = indicador['dolent'];
      molt_dolent = indicador['molt_dolent'];

      bo = bo.split(" ");
      moderat = moderat.split(" ");
      regular = regular.split(" ");
      dolent = dolent.split(" ");
      molt_dolent = molt_dolent.split(" ");

      bo = bo[0].split("-");
      moderat = moderat[0].split("-");
      regular = regular[0].split("-");
      dolent = dolent[0].split("-");
      molt_dolent = molt_dolent[0].split("-");

      if(valor!==null){
          if(valor<parseInt(bo[1])){
              qualificacio = 'bo';
          }else if (valor<parseInt(moderat[1])){
              qualificacio = 'moderat';
          }else if (valor<parseInt(regular[1])){
              qualificacio = 'regular';
          }else if (valor<parseInt(dolent[1])){
              qualificacio = 'dolent';
          }else{
            qualificacio ='molt dolent';
          }
      }
    }else{
      qualificacio = valor;
    }

    if(indicador['nom'] == 'ICQA'){
      switch(qualificacio) {
        case 'bo':
          qualificacio = 'bo';
          break;
        case 'moderat':
          qualificacio = 'regular';
          break;
        case 'regular':
          qualificacio = 'dolent';
          break;
        case 'dolent':
          qualificacio = 'molt dolent';
          break;
        case 'molt_dolent':
          qualificacio = 'molt dolent';
        case 'molt dolent':
          qualificacio = 'molt dolent';
          break;
        default:
          qualificacio = null;
          break;
      }
    }
  }

  return qualificacio
}

  export default Checker;