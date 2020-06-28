<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

use App\Models\Contaminant;
use App\Models\Estacio;
use App\Models\Mostra;
use App\Models\Immissio;
use App\Models\Indicador;
use App\Models\Resultat;

class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public static function getResponse($response){
        $response = json_encode($response); 
        $response = json_decode($response, true);
        return $response['original'];
    }

    public function createResults($isCron = false){
        $contaminants = DataController::getResponse(ContaminantController::showAllContaminants());
        $parametres = [];
        $parametres['contaminants'] = [];
        $resultats = [];
        $base=$endpoint ='/api/contaminants/';
        
        $any = (int) date("Y");
        $mes = (int) date("m");
        $dia = (int) date("d");
        $current = '?dia='.$dia.'&mes='.$mes.'&any='.$any;
        $contaminant_count = 0;
        foreach($contaminants as $contaminant){

            $contaminant_id = $contaminant['id'];
            $base=$endpoint = $endpoint.$contaminant_id;
            $endpoint = $endpoint.'/immissions';
            
            $parametres['contaminants'][$contaminant_count]['contaminant'] = [];
            $parametres['contaminants'][$contaminant_count]['contaminant'] = $contaminant_id;
            $parametres['contaminants'][$contaminant_count]['indicadors'] = [];
            $indicadors = DataController::getResponse(ContaminantController::showAllIndicadorsFromContaminant($contaminant_id, new Request));

            $indicador_count = 0;
            foreach ($indicadors as $indicador){
                $indicador_id = $indicador['id'];
                $parametres['contaminants'][$contaminant_count]['indicadors'][$indicador_count]=$indicador_id;
                $indicador_count++;
                $indicador = DataController::getResponse(ContaminantController::showIndicadorFromContaminant($contaminant_id, $indicador_id));
                
                if($isCron){
                    $endpoint = $endpoint.$current;
                }else{
                    $endpoint = $endpoint.$current;
                }
                $immissions = DataController::getResponse(ContaminantController::showAllImmissionsFromContaminant($contaminant_id,Request::create($endpoint, 'GET')));

                foreach ($immissions as $immissio){
                    $immissio_id = $immissio['id'];
                    $mostra = DataController::getResponse(MostraController::showOneMostra($immissio_id));
                    $endpoint = '/api/immissions/'.$immissio_id.'/resultats?indicador_id='.$indicador_id;
                    $resultat= DataController::getResponse(ImmissioController::showAllResultatsFromImmissio($immissio_id,Request::create($endpoint, 'GET')));
                    $mostra_resultat = null;
                    $resultat_id = 'id';
                    if(!empty($resultat)){
                        $resultat_id=reset($resultat)['id'];
                    }
                    
                    $request_type=empty($resultat)?'POST':'PUT';
                    if(!empty(DataController::getResponse(MostraController::showAllResultatsFromMostra($immissio_id,Request::create('/api/mostres/'.$immissio_id.'/resultats?indicador_id='.$indicador_id, 'GET'))))){
                        $request_type='PUT';
                    }
                    $endpoint = $request_type == 'POST'?'/api/resultats':'/api/resultats/'.$resultat_id;
                    $request = Request::create($endpoint, $request_type);

                    if(!empty($mostra)){
                        $mostra_id = $mostra['id'];
                        $mostra_resultat = DataController::getResult($indicador_id, $indicador, $mostra_id, $mostra);
                    }
                    
                    $resultat=$immissio_resultat = DataController::getResult($indicador_id, $indicador, $immissio_id, $immissio);

                    if($mostra_resultat!=null){
                        $resultat = DataController::combineResults($immissio_resultat, $mostra_resultat);
                        $request->request->add([
                            'mostra_id' => $mostra_id,
                        ]);
                    }
                    $request->request->add([
                        'immissio_id' => $immissio_id

                    ]);
                    $request->request->add($resultat);
                    set_time_limit(300);
                    if($isCron){
                        $response = $request_type=='PUT'? ResultatController::updateResultat($request->only('id')['id'], $request):  ResultatController::createResultat($request);
                    }
                    else{
                        $response = app()->handle($request); 
                    }
                    $response_json = json_encode($response); 
                    $result = json_decode($response_json, true); 
                    array_push($resultats,$response->status());
                }

                if(true){
                    $mostres = DataController::getResponse(ContaminantController::showAllMostresFromContaminant($contaminant_id,Request::create($base.'/mostres?mes='.$mes.'&any='.$any, 'GET')));
                    foreach ($mostres as $mostra){
                        $mostra_id = $mostra['id'];
                        $resultat = DataController::getResponse(MostraController::showAllResultatsFromMostra($mostra_id,Request::create('/api/mostres/'.$mostra_id.'/resultats?indicador_id='.$indicador_id, 'GET')));
                        $request_type=empty($resultat)?'POST':'PUT';
                        $resultat_id = 'id';
                        if(!empty($resultat)){
                            $resultat_id=reset($resultat)['id'];
                        }
                        $endpoint = $request_type == 'POST'?'/api/resultats':'/api/resultats/'.$resultat_id;
                        $request = Request::create($endpoint, $request_type);
                        $mostra_resultat = DataController::getResult($indicador_id, $indicador, $mostra_id, $mostra);
                        $request->request->add([
                            'mostra_id' => $mostra_id
                        ]);
                        $request->request->add($mostra_resultat);
                        $response = $request_type=='PUT'? ResultatController::updateResultat($request->only('id')['id'], $request):  ResultatController::createResultat($request);
                        $response_json = json_encode($response); 
                        $result = json_decode($response_json, true); 
                        array_push($resultats,$response->status());
                    }
                }
            }
            $contaminant_count++;               
        }
        echo json_encode($resultats);
    }

    private function getResult($indicador_id, $indicador,$immissio_id, $immissio){
        unset($immissio["id"]);     
        unset($immissio["created_at"]);
        unset($immissio["updated_at"]);
       
        $H01 = $immissio['H01'];
        $H02 = $immissio['H02'];
        $H03 = $immissio['H03'];
        $H04 = $immissio['H04'];
        $H05 = $immissio['H05'];
        $H06 = $immissio['H06'];
        $H07 = $immissio['H07'];
        $H08 = $immissio['H08'];
        $H09 = $immissio['H09'];
        $H10 = $immissio['H10'];
        $H11 = $immissio['H11'];
        $H12 = $immissio['H12'];
        $H13 = $immissio['H13'];
        $H14 = $immissio['H14'];
        $H15 = $immissio['H15'];
        $H16 = $immissio['H16'];
        $H17 = $immissio['H17'];
        $H18 = $immissio['H18'];
        $H19 = $immissio['H19'];
        $H20 = $immissio['H20'];
        $H21 = $immissio['H21'];
        $H22 = $immissio['H22'];
        $H23 = $immissio['H23'];
        $H24 = $immissio['H24'];

        $V01 = DataController::getQualification($indicador, $H01);
        $V02 = DataController::getQualification($indicador, $H02);
        $V03 = DataController::getQualification($indicador, $H03);
        $V04 = DataController::getQualification($indicador, $H04);
        $V05 = DataController::getQualification($indicador, $H05);
        $V06 = DataController::getQualification($indicador, $H06);
        $V07 = DataController::getQualification($indicador, $H07);
        $V08 = DataController::getQualification($indicador, $H08);
        $V09 = DataController::getQualification($indicador, $H09);
        $V10 = DataController::getQualification($indicador, $H10);
        $V11 = DataController::getQualification($indicador, $H11);
        $V12 = DataController::getQualification($indicador, $H12);
        $V13 = DataController::getQualification($indicador, $H13);
        $V14 = DataController::getQualification($indicador, $H14);
        $V15 = DataController::getQualification($indicador, $H15);
        $V16 = DataController::getQualification($indicador, $H16);
        $V17 = DataController::getQualification($indicador, $H17);
        $V18 = DataController::getQualification($indicador, $H18);
        $V19 = DataController::getQualification($indicador, $H19);
        $V20 = DataController::getQualification($indicador, $H20);
        $V21 = DataController::getQualification($indicador, $H21);
        $V22 = DataController::getQualification($indicador, $H22);
        $V23 = DataController::getQualification($indicador, $H23);
        $V24 = DataController::getQualification($indicador, $H24);
        $request = Request::create('/api/resultats', 'POST');
        $request->request->add($immissio);
        $request->request->add([
            'id' => $immissio_id.'_'.$indicador_id,
            'indicador_id' => $indicador_id,
            'R01' => $V01,
            'R02' => $V02,
            'R03' => $V03,
            'R04' => $V04,
            'R05' => $V05,
            'R06' => $V06,
            'R07' => $V07,
            'R08' => $V08,
            'R09' => $V09,
            'R10' => $V10,
            'R11' => $V11,
            'R12' => $V12,
            'R13' => $V13,
            'R14' => $V14,
            'R15' => $V15,
            'R16' => $V16,
            'R17' => $V17,
            'R18' => $V18,
            'R19' => $V19,
            'R20' => $V20,
            'R21' => $V21,
            'R22' => $V22,
            'R23' => $V23,
            'R24' => $V24
        ]);
        $resposta = DataController::getGeneralQualification($request->all());
        $maxim = $resposta['maxim'] ;
        $minim = $resposta['minim'];
        $mitjana = $resposta['mitjana'];
        $complet = $resposta['complet'];
        $valid = $resposta['valid'];
        $qualificacio = DataController::getQualification($indicador, $mitjana);

        $request->request->add([
            'maxim' => $maxim,
            'minim' => $minim,
            'mitjana'=> $mitjana,
            'qualificacio'=> $qualificacio,
            'complet'=> $valid,
            'valid'=> $valid
        ]);
        return $request->all();
    }

    private function getQualification($indicador, $valor){
        $bo = $indicador['bo'];
        $moderat = $indicador['moderat'];
        $regular = $indicador['regular'];
        $dolent = $indicador['dolent'];
        $molt_dolent = $indicador['molt_dolent'];

        $bo = explode(" ",$bo);
        $moderat = explode(" ",$moderat);
        $regular = explode(" ",$regular);
        $dolent = explode(" ", $dolent);
        $molt_dolent = explode(" ",$molt_dolent);

        $bo = explode("-",$bo[0]);
        $moderat = explode("-",$moderat[0]);
        $regular = explode("-",$regular[0]);
        $dolent = explode("-", $dolent[0]);
        $molt_dolent = explode("-",$molt_dolent[0]);

        $qualificacio = null;
        if($valor!==null){
            if($valor<intval($bo[1])){
                $qualificacio = 'bo';
            }else if ($valor<intval($moderat[1])){
                $qualificacio = 'moderat';
            }else if ($valor<intval($regular[1])){
                $qualificacio = 'regular';
            }else if ($valor<intval($dolent[1])){
                $qualificacio = 'dolent';
            }else{
                $qualificacio = 'molt dolent';
            }
        }
        return $qualificacio;
    }

    private function getGeneralQualification($parametres){
        $maxim_valor = 0;
        $minim_valor = 999;

        $maxim = '';
        $minim = '';
        $mitjana = 0;

        $valids = 0;
        $valors = 0;
        foreach($parametres as $parametre => $valor){
            if (strpos($parametre, 'H') !== false) {
                if($valor !== null){
                    $valors++;
                    $mitjana+=$valor;
                    if($valor>$maxim_valor){
                        $maxim_valor = $valor;
                        $maxim = $parametre;
                    } 
                    if($valor<$minim_valor){
                        $minim_valor = $valor;
                        $minim = $parametre;
                    }
                    $valid_id = str_replace("H","V",$parametre);
                    if($parametres[$valid_id]=="V"){
                        $valids++;
                    }
                }
            }
        }
        $mitjana = $valors!=0?(float)$mitjana/$valors:0;
        $complet = $valors == 24;
        $valid = ($complet && $valids == 24);
        
        $resposta = [];
        $resposta['maxim'] = $maxim;
        $resposta['minim'] = $minim;
        $resposta['mitjana'] = round($mitjana, 2);
        $resposta['complet'] = $complet;
        $resposta['valid'] = $valid;
        $resposta['quantitat'] = $valors;
        return $resposta;
    }

    private function combineResults($immissio_resultat, $mostra_resultat){
        $valid_mostra=0;
        $valid_resultat=0;

        foreach ($immissio_resultat as $atribut){
           if($atribut==="V"){
               $valid_resultat+=1;
           }
        }
        foreach ($mostra_resultat as $atribut){
            if($atribut==="V"){
                $valid_mostra+=1;
            }
         }

        if($valid_mostra>$valid_resultat){
            return $mostra_resultat;
        }else{
            return $immissio_resultat;
        }
    }

    public function createCongestions($isCron = false){
        $trams = DataController::getResponse(TramController::showAllTrams());

        $resultats = [];
       
        $base=$endpoint ='/api/trams/';
        
        $any = (int) date("Y");
        $mes = (int) date("m");
        $dia = (int) date("d");
        $hora = (int) date("H");
        $hora+=2;
        if($hora==24){
            $hora=0;
        }
        
        $current = '?hora='.($hora).'&dia='.($dia).'&mes='.$mes.'&any='.$any;
        
        $tram_count = 0;
        foreach($trams as $tram){

            $tram_id = $tram['id'];

            $base=$endpoint = $endpoint.$tram_id; 
            $endpoint = $endpoint.'/estats'; 
            
            if($isCron){
                $endpoint = $endpoint.$current;
            }else{
                $endpoint = $endpoint.$current;
            }
            set_time_limit(14400);
            $estats = DataController::getResponse(TramController::showAllEstatsFromTram($tram_id,Request::create($endpoint, 'GET')));
            foreach ($estats as $estat){
                $estat_id=$estat['id'];
                $c = DataController::getResponse(EstatController::showCongestioFromEstat($estat_id));
                if(empty($c)){
                    $any = $estat['any'];
                    $mes = sprintf("%02d", $estat['mes']);
                    $dia = sprintf("%02d", $estat['dia']);
                    $hora = sprintf("%02d", $estat['hora']);
                    $actual = $estat['estat_actual'];
                    $previst = $estat['estat_previst'];
                    $congestio_id=$any.$mes.$dia.'_'.$estat['tram_id'];
                    $endpoint = '/api/congestions/'.$congestio_id;
                    $congestio= DataController::getResponse(CongestioController::showOneCongestio($congestio_id));
                    $request_type=empty($congestio)?'POST':'PUT';
                    $endpoint = $request_type == 'POST'?'/api/congestions':'/api/congestions/'.$congestio_id;
                    $request = Request::create($endpoint, $request_type);
                    set_time_limit(14400);
                    if($request_type=='POST'){
                        $request->request->add([
                        'id' => $congestio_id,
                        'tram_id' => $tram_id,
                        'any' => $estat['any'],
                        'mes' => $estat['mes'],
                        'dia' => $estat['dia'],
                        'H01' => DataController::setEstat('01',$hora,$actual),
                        'H02' => DataController::setEstat('02',$hora,$actual),
                        'H03' => DataController::setEstat('03',$hora,$actual),
                        'H04' => DataController::setEstat('04',$hora,$actual),
                        'H05' => DataController::setEstat('05',$hora,$actual),
                        'H06' => DataController::setEstat('06',$hora,$actual),
                        'H07' => DataController::setEstat('07',$hora,$actual),
                        'H08' => DataController::setEstat('08',$hora,$actual),
                        'H09' => DataController::setEstat('09',$hora,$actual),
                        'H10' => DataController::setEstat('10',$hora,$actual),
                        'H11' => DataController::setEstat('11',$hora,$actual),
                        'H12' => DataController::setEstat('12',$hora,$actual),
                        'H13' => DataController::setEstat('13',$hora,$actual),
                        'H14' => DataController::setEstat('14',$hora,$actual),
                        'H15' => DataController::setEstat('15',$hora,$actual),
                        'H16' => DataController::setEstat('16',$hora,$actual),
                        'H17' => DataController::setEstat('17',$hora,$actual),
                        'H18' => DataController::setEstat('18',$hora,$actual),
                        'H19' => DataController::setEstat('19',$hora,$actual),
                        'H20' => DataController::setEstat('20',$hora,$actual),
                        'H21' => DataController::setEstat('21',$hora,$actual),
                        'H22' => DataController::setEstat('22',$hora,$actual),
                        'H23' => DataController::setEstat('23',$hora,$actual),
                        'H24' => DataController::setEstat('00',$hora,$actual),
                        'P01' => DataController::setEstat('01',$hora,$previst),
                        'P02' => DataController::setEstat('02',$hora,$previst),
                        'P03' => DataController::setEstat('03',$hora,$previst),
                        'P04' => DataController::setEstat('04',$hora,$previst),
                        'P05' => DataController::setEstat('05',$hora,$previst),
                        'P06' => DataController::setEstat('06',$hora,$previst),
                        'P07' => DataController::setEstat('07',$hora,$previst),
                        'P08' => DataController::setEstat('08',$hora,$previst),
                        'P09' => DataController::setEstat('09',$hora,$previst),
                        'P10' => DataController::setEstat('10',$hora,$previst),
                        'P11' => DataController::setEstat('11',$hora,$previst),
                        'P12' => DataController::setEstat('12',$hora,$previst),
                        'P13' => DataController::setEstat('13',$hora,$previst),
                        'P14' => DataController::setEstat('14',$hora,$previst),
                        'P15' => DataController::setEstat('15',$hora,$previst),
                        'P16' => DataController::setEstat('16',$hora,$previst),
                        'P17' => DataController::setEstat('17',$hora,$previst),
                        'P18' => DataController::setEstat('18',$hora,$previst),
                        'P19' => DataController::setEstat('19',$hora,$previst),
                        'P20' => DataController::setEstat('20',$hora,$previst),
                        'P21' => DataController::setEstat('21',$hora,$previst),
                        'P22' => DataController::setEstat('22',$hora,$previst),
                        'P23' => DataController::setEstat('23',$hora,$previst),
                        'P24' => DataController::setEstat('00',$hora,$previst),
                        'actual' => $actual,
                        'previst' => $previst,
                        'complet' => false,
                    ]);
                    }else{
                        $congestio_actual_hora=$congestio[($hora=='00'?'H24':'H'.$hora)];
                        $congestio_previst_hora=$congestio[($hora=='00'?'P24':'P'.$hora)];
                        if($congestio_actual_hora !== null){
                            if($congestio_actual_hora == 0){
                                if ($actual>0){
                                    $actual = (($actual+$congestio_actual_hora)/2);
                                }
                            }else if ($congestio_actual_hora>0){
                                $actual = (($actual+$congestio_actual_hora)/2);
                            }
                        }
                        if($congestio_previst_hora !== null){
                            if($congestio_previst_hora == 0){
                                if ($previst>0){
                                    $previst = (($previst+$congestio_previst_hora)/2);
                                }
                            }else if ($congestio_actual_hora>0){
                                $previst = (($previst+$congestio_previst_hora)/2);
                            }
                        }
                        $request->request->add([
                            'id' => $congestio_id,
                            'tram_id' => $tram_id,
                            ($hora=='00'?'H24':'H'.$hora) => $actual,
                            ($hora=='00'?'P24':'P'.$hora) => $previst,
                            'actual' => DataController::getTotal($congestio,'H'.$hora,$actual),
                            'previst' => DataController::getTotal($congestio,'P'.$hora,$previst),
                            'complet' =>  DataController::isComplet($congestio,'H'.$hora,'P'.$hora)
                        ]);
                    }
                    set_time_limit(14400);

                    $response = $request_type=='PUT'? CongestioController::updateCongestio($request->only('id')['id'], $request):CongestioController::createCongestio($request);

                    $request2 = Request::create('api/estats/'.$estat_id, 'PUT');
                    $request2->request->add([
                        'congestio_id' => $congestio_id
                    ]);
                    $response2 = EstatController::updateEstat($estat_id, $request2);
                    var_dump( "Final" );
                    var_dump( ($tram_id."-".$congestio_id."-".empty($c)) );
                }
            }
        }
    }

    private static function setEstat($atribut,$hora,$valor){
        if($atribut == $hora){
            return $valor;
        }
        else{
            return null;
        }
    }
    private static function isComplet($congestio, $actual, $previst){
        $count_actual = 0;
        $count_previst = 0;
        $total = 0;
        foreach($congestio as $atribut => $valor){
            if($atribut[0]=='H' && $valor!==null){
                $count_actual++;
                $total++;
            }else if ($atribut[0]=='P' && $valor!==null){
                $count_previst++;
                $total++;
            }
        }
        if($actual=='H00' || $previst=='P00'){
            $actual='H24';
            $previst='P24';
        }

        if($congestio[$actual]===null){
            $count_actual++;
            $total++;
        }
        if($congestio[$previst]===null){
            $count_previst++;
            $total++;
        }
        return $count_actual == 24 && $count_previst == 24;
    }

    private static function getTotal($congestio, $key, $nou){
        $suma=0;
        $count=0;
        foreach($congestio as $atribut => $valor){
            if($atribut[0]==$key[0] && $valor!==null){
                if($atribut==$key){
                    $valor= (($valor+$nou)/2);
                }
                $suma+=$valor;
                $count++;
            }
        }
        if($key=='H00'){
            $key='H24';
        }else if($key=='P00'){
            $key='P24';
        }

        if($congestio[$key]===null){
            $suma+=$nou;
                $count++;
        }
        return $suma/$count;
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
