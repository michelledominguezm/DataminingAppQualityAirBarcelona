<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class ApiController extends Controller
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

    public function callAPI($base_uri, $uri, $type) 
    {
        $client = new Client([
            'base_uri'=>$base_uri,
            'verify'=>false,
            'headers' => [
                "Content-Type" => "application/json;charset=utf-8",
                'Accept' => 'application/json'
                ]
        ]);

        $response = $client->get($uri);
        $statusCode = $response->getStatusCode();//check if status OK

        $body = $response->getBody()->getContents();

        if ($type =="csv"){
            $result = ApiController::csvToJson($body);
            return $result; 
            
        }else if ($type=="data"){
            $result = ApiController::dataToJson($body);
            return $result; 
        }else{
            return json_decode($body,true);
        }
    }

    public function getAirQualityDataJSON() 
    {   
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "api/action/datastore_search?resource_id=c2032e7c-10ee-4c69-84d3-9e8caf9ca97a";

        $response_data = [];
        
        $response = ApiController::callAPI($base_uri, $uri,'');
        $pages = 0;
        if($response['success']){
            while(!empty($response['result']['records'])){
                $result = $response['result']; //Array
                $fields = $result['fields']; //Array
                $records = $result['records']; //Array
                $nextUri = $result['_links']['next'];//String
                $response_data[$pages]=$records;
                $response = ApiController::callAPI($base_uri,  substr($nextUri, 1),'');
                $pages++;
            }
        }
        //return $response_data;
    }

    public function getAirQualityDataCSV($isCron = false) 
    {   
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "dataset/0582a266-ea06-4cc5-a219-913b22484e40/resource/c2032e7c-10ee-4c69-84d3-9e8caf9ca97a/download";

        $response_data = [];
        
        $response_data = ApiController::callAPI($base_uri, $uri,'csv');
        ApiController::processAirQualityData($response_data, $isCron);  

        //return $response_data;
    }

    private function processAirQualityData($data,$isCron){
        $response_array = [];
        $current_day = (int) date("d");
        $current_hour = (int) date("d");
        foreach ($data as $mostra){     
            $estacio_id = intval($mostra['ESTACIO']);
            $contaminant_id = intval($mostra['CODI_CONTAMINANT']);     
            $any = ($mostra['ANY']);
            $mes = ($mostra['MES']);
            $dia = ($mostra['DIA']);
            $H01 = ApiController::checkAirQualityValue(1,(double)$mostra['H01'],$dia,$current_day);
            $V01 = $mostra['V01'];
            $H02 = ApiController::checkAirQualityValue(2,(double)$mostra['H02'],$dia,$current_day);
            $V02 = $mostra['V02'];
            $H03 = ApiController::checkAirQualityValue(3,(double)$mostra['H03'],$dia,$current_day);
            $V03 = $mostra['V03'];
            $H04 = ApiController::checkAirQualityValue(4,(double)$mostra['H04'],$dia,$current_day);
            $V04 = $mostra['V04'];
            $H05 = ApiController::checkAirQualityValue(5,(double)$mostra['H05'],$dia,$current_day);
            $V05 = $mostra['V05'];
            $H06 = ApiController::checkAirQualityValue(6,(double)$mostra['H06'],$dia,$current_day);
            $V06 = $mostra['V06'];
            $H07 = ApiController::checkAirQualityValue(7,(double)$mostra['H07'],$dia,$current_day);
            $V07 = $mostra['V07'];
            $H08 = ApiController::checkAirQualityValue(8,(double)$mostra['H08'],$dia,$current_day);
            $V08 = $mostra['V08'];
            $H09 = ApiController::checkAirQualityValue(9,(double)$mostra['H09'],$dia,$current_day);
            $V09 = $mostra['V09'];
            $H10 = ApiController::checkAirQualityValue(10,(double)$mostra['H10'],$dia,$current_day);
            $V10 = $mostra['V10'];
            $H11 = ApiController::checkAirQualityValue(11,(double)$mostra['H11'],$dia,$current_day);
            $V11 = $mostra['V11'];
            $H12 = ApiController::checkAirQualityValue(12,(double)$mostra['H12'],$dia,$current_day);
            $V12 = $mostra['V12'];
            $H13 = ApiController::checkAirQualityValue(13,(double)$mostra['H13'],$dia,$current_day);
            $V13 = $mostra['V13'];
            $H14 = ApiController::checkAirQualityValue(14,(double)$mostra['H14'],$dia,$current_day);
            $V14 = $mostra['V14'];
            $H15 = ApiController::checkAirQualityValue(15,(double)$mostra['H15'],$dia,$current_day);
            $V15 = $mostra['V15'];
            $H16 = ApiController::checkAirQualityValue(16,(double)$mostra['H16'],$dia,$current_day);
            $V16 = $mostra['V16'];
            $H17 = ApiController::checkAirQualityValue(17,(double)$mostra['H17'],$dia,$current_day);
            $V17 = $mostra['V17'];
            $H18 = ApiController::checkAirQualityValue(18,(double)$mostra['H18'],$dia,$current_day);
            $V18 = $mostra['V18'];
            $H19 = ApiController::checkAirQualityValue(19,(double)$mostra['H19'],$dia,$current_day);
            $V19 = $mostra['V19'];
            $H20 = ApiController::checkAirQualityValue(20,(double)$mostra['H20'],$dia,$current_day);
            $V20 = $mostra['V20'];
            $H21 = ApiController::checkAirQualityValue(21,(double)$mostra['H21'],$dia,$current_day);
            $V21 = $mostra['V21'];
            $H22 = ApiController::checkAirQualityValue(22,(double)$mostra['H22'],$dia,$current_day);
            $V22 = $mostra['V22'];
            $H23 = ApiController::checkAirQualityValue(23,(double)$mostra['H23'],$dia,$current_day);
            $V23 = $mostra['V23'];
            $H24 = ApiController::checkAirQualityValue(24,(double)$mostra['H24'],$dia,$current_day);
            $V24 = $mostra['V24'];
                      
            $data = $any;
            $data .= '-';
            $data .= str_pad($mes,2,"0",STR_PAD_LEFT); //when day or month <10, adds 0 before
            $data .= '-';
            $data .= str_pad($dia,2,"0",STR_PAD_LEFT);
            $data .= 'T00:00:00.000';

            $id = $mostra['CODI_PROVINCIA'];
            $id .= $mostra['CODI_MUNICIPI'];
            $id .= str_pad($estacio_id,3,"0",STR_PAD_LEFT);
            $id .= '_';
            $id .= $contaminant_id;
            $id .= '_';
            $id .= $any;
            $id .= str_pad($mes,2,"0",STR_PAD_LEFT);
            $id .= str_pad($dia,2,"0",STR_PAD_LEFT);      

            $any = intval($mostra['ANY']);
            $mes = intval($mostra['MES']);
            $dia = intval($mostra['DIA']);                      

            $request_type=ApiController::getRequestType('/api/mostres/'.$id);
            $endpoint = $request_type == 'POST'?'/api/mostres':'/api/mostres/'.$id;
            $request = Request::create($endpoint, $request_type);
            $request->request->add([
                'id' => $id,
                'estacio_id' => $estacio_id,
                'contaminant_id' => $contaminant_id,
                'data' => $data,
                'any' => $any,
                'mes' => $mes,
                'dia' => $dia,
                'H01' => $H01,
                'V01' => $V01,
                'H02' => $H02,
                'V02' => $V02,
                'H03' => $H03,
                'V03' => $V03,
                'H04' => $H04,
                'V04' => $V04,
                'H05' => $H05,
                'V05' => $V05,
                'H06' => $H06,
                'V06' => $V06,
                'H07' => $H07,
                'V07' => $V07,
                'H08' => $H08,
                'V08' => $V08,
                'H09' => $H09,
                'V09' => $V09,
                'H10' => $H10,
                'V10' => $V10,
                'H11' => $H11,
                'V11' => $V11,
                'H12' => $H12,
                'V12' => $V12,
                'H13' => $H13,
                'V13' => $V13,
                'H14' => $H14,
                'V14' => $V14,
                'H15' => $H15,
                'V15' => $V15,
                'H16' => $H16,
                'V16' => $V16,
                'H17' => $H17,
                'V17' => $V17,
                'H18' => $H18,
                'V18' => $V18,
                'H19' => $H19,
                'V19' => $V19,
                'H20' => $H20,
                'V20' => $V20,
                'H21' => $H21,
                'V21' => $V21,
                'H22' => $H22,
                'V22' => $V22,
                'H23' => $H23,
                'V23' => $V23,
                'H24' => $H24,
                'V24' => $V24
            ]);

            $cotaminant_exceptions = [22,999,997,996,998];
            if(!in_array($contaminant_id,$cotaminant_exceptions)){

                if($isCron){
                    $response = $request_type=='PUT'? MostraController::updateMostra($id,  $request):  MostraController::createMostra($request);
                }
                else{
                    $response = app()->handle($request); 
                }
            
                $response_json = json_encode($response); 
                $result = json_decode($response_json, true); 

                array_push($response_array,$response->status());
            }
        }
        echo json_encode($response_array);
    }

    private function checkAirQualityValue($hour, $value, $day, $current_day){
        $current_hour = (date('H'));
        $current_hour+=2;

        if($current_hour == 0){
            $current_hour = 24;
        }

        if ($day == $current_day && $value==0 && $hour>=$current_hour){
            return null;
        }
        return $value;
    }

    public function getAirPollutionData($isCron=false) 
    {
        $oldestYear = (int) date("Y");
        $oldestYear-=1;
        $currentMonth = (int) date("m");

        $base_uri = "https://analisi.transparenciacatalunya.cat/";
        $uri = "resource/uy6k-2s8r.json?municipi=Barcelona&$"."where=any > ".$oldestYear."&mes=".$currentMonth."&$";
        //$uri = "resource/uy6k-2s8r.json?municipi=Barcelona&$"."where=mes>8&any=2015&$";

        $limit = 10000;
        $offset = 0;
        
        $newUri = $uri.'limit='.$limit.'&$'.'offset='.$offset;

        $response_data = [];
        $response = ApiController::callAPI($base_uri, $newUri,'');
        $pages = 0;
        $result = null;
            
        while(!empty($response)){
            $response_data[$pages] = $response;
            $offset+=$limit;
            $nextUri = $uri.'limit='.$limit.'&$'.'offset='.$offset;
            ApiController::processAirPollutionData($response, $isCron);
            $response = ApiController::callAPI($base_uri, $nextUri,'');
            $pages++;
        }
    }

    private function processAirPollutionData($data,$isCron){
        $response_array = [];

        foreach ($data as $mostra){   
            $id = $mostra['codi_mesurament'];  
            $estacio_id = intval($mostra['codi_estaci']);
            $contaminant_id = intval($mostra['magnitud']);
            $data = $mostra['data'];     
            $any = intval($mostra['any']);
            $mes = intval($mostra['mes']);
            $dia = intval($mostra['dia']);
            $H01 = isset($mostra['h01'])?$mostra['h01']:null;
            $V01 = $mostra['v01'];
            $H02 = isset($mostra['h02'])?$mostra['h02']:null;
            $V02 = $mostra['v02'];
            $H03 = isset($mostra['h03'])?$mostra['h03']:null;
            $V03 = $mostra['v03'];
            $H04 = isset($mostra['h04'])?$mostra['h04']:null;
            $V04 = $mostra['v04'];
            $H05 = isset($mostra['h05'])?$mostra['h05']:null;
            $V05 = $mostra['v05'];
            $H06 = isset($mostra['h06'])?$mostra['h06']:null;
            $V06 = $mostra['v06'];
            $H07 = isset($mostra['h07'])?$mostra['h07']:null;
            $V07 = $mostra['v07'];
            $H08 = isset($mostra['h08'])?$mostra['h08']:null;
            $V08 = $mostra['v08'];
            $H09 = isset($mostra['h09'])?$mostra['h09']:null;
            $V09 = $mostra['v09'];
            $H10 = isset($mostra['h10'])?$mostra['h10']:null;
            $V10 = $mostra['v10'];
            $H11 = isset($mostra['h11'])?$mostra['h11']:null;
            $V11 = $mostra['v11'];
            $H12 = isset($mostra['h12'])?$mostra['h12']:null;
            $V12 = $mostra['v12'];
            $H13 = isset($mostra['h13'])?$mostra['h13']:null;
            $V13 = $mostra['v13'];
            $H14 = isset($mostra['h14'])?$mostra['h14']:null;
            $V14 = $mostra['v14'];
            $H15 = isset($mostra['h15'])?$mostra['h15']:null;
            $V15 = $mostra['v15'];
            $H16 = isset($mostra['h16'])?$mostra['h16']:null;
            $V16 = $mostra['v16'];
            $H17 = isset($mostra['h17'])?$mostra['h17']:null;
            $V17 = $mostra['v17'];
            $H18 = isset($mostra['h18'])?$mostra['h18']:null;
            $V18 = $mostra['v18'];
            $H19 = isset($mostra['h19'])?$mostra['h19']:null;
            $V19 = $mostra['v19'];
            $H20 = isset($mostra['h20'])?$mostra['h20']:null;
            $V20 = $mostra['v20'];
            $H21 = isset($mostra['h21'])?$mostra['h21']:null;
            $V21 = $mostra['v21'];
            $H22 = isset($mostra['h22'])?$mostra['h22']:null;
            $V22 = $mostra['v22'];
            $H23 = isset($mostra['h23'])?$mostra['h23']:null;
            $V23 = $mostra['v23'];
            $H24 = isset($mostra['h24'])?$mostra['h24']:null;
            $V24 = $mostra['v24'];                      

            $request_type=ApiController::getRequestType('/api/immissions/'.$id);
            $endpoint = $request_type == 'POST'?'/api/immissions':'/api/immissions/'.$id;
            $request = Request::create($endpoint, $request_type);
            $request->request->add([
                'id' => $id,
                'estacio_id' => $estacio_id,
                'contaminant_id' => $contaminant_id,
                'data' => $data,
                'any' => $any,
                'mes' => $mes,
                'dia' => $dia,
                'H01' => $H01,
                'V01' => $V01,
                'H02' => $H02,
                'V02' => $V02,
                'H03' => $H03,
                'V03' => $V03,
                'H04' => $H04,
                'V04' => $V04,
                'H05' => $H05,
                'V05' => $V05,
                'H06' => $H06,
                'V06' => $V06,
                'H07' => $H07,
                'V07' => $V07,
                'H08' => $H08,
                'V08' => $V08,
                'H09' => $H09,
                'V09' => $V09,
                'H10' => $H10,
                'V10' => $V10,
                'H11' => $H11,
                'V11' => $V11,
                'H12' => $H12,
                'V12' => $V12,
                'H13' => $H13,
                'V13' => $V13,
                'H14' => $H14,
                'V14' => $V14,
                'H15' => $H15,
                'V15' => $V15,
                'H16' => $H16,
                'V16' => $V16,
                'H17' => $H17,
                'V17' => $V17,
                'H18' => $H18,
                'V18' => $V18,
                'H19' => $H19,
                'V19' => $V19,
                'H20' => $H20,
                'V20' => $V20,
                'H21' => $H21,
                'V21' => $V21,
                'H22' => $H22,
                'V22' => $V22,
                'H23' => $H23,
                'V23' => $V23,
                'H24' => $H24,
                'V24' => $V24
            ]);
            set_time_limit(300);
            $cotaminant_exceptions = [22,999,997,996,998];
            if(!in_array($contaminant_id,$cotaminant_exceptions)){
                if($isCron){
                    $response = $request_type=='PUT'? ImmissioController::updateImmissio($id,  $request):  ImmissioController::createImmissio($request);
                }
                else{
                    $response = app()->handle($request); 
                }

                $response_json = json_encode($response); 
                $result = json_decode($response_json, true); 
                array_push($response_array,$response->status());
            }
        }
        echo json_encode($response_array);
    }

    public function getAirPollutantsData($isCron = false) 
    {
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "api/action/datastore_search?resource_id=c122329d-d26d-469e-bf9e-8efa10e4c127";

        $response_data = [];
        
        $response = ApiController::callAPI($base_uri, $uri,'');
        $pages = 0;
        if($response['success']){
            while(!empty($response['result']['records'])){
                $result = $response['result']; 
                $fields = $result['fields']; 
                $records = $result['records']; 
                $nextUri = $result['_links']['next'];
                $response_data[$pages]=$records;
                ApiController::processAirPollutantsData($records,$isCron);
                $response = ApiController::callAPI($base_uri,  substr($nextUri, 1),'');
                $pages++;
            }  
        }
    }

    private function processAirPollutantsData($data,$isCron){
        $response_array = [];
        foreach ($data as $contaminant){
            $id = intval($contaminant['Codi_Contaminant']);
            $simbol = $contaminant['Desc_Contaminant'];
            $unitats = $contaminant['Unitats'];
            $request_type=ApiController::getRequestType('/api/contaminants/'.$id);
            $endpoint = $request_type == 'POST'?'/api/contaminants':'/api/contaminants/'.$id;
            $request = Request::create($endpoint, $request_type);
            $request->request->add([
                'id' => $id,
                'simbol' => $simbol,
                'unitats' => $unitats
            ]);
            if($isCron){
                $response = $request_type=='PUT'? ContaminantController::updateContaminant($id,  $request):  ContaminantController::createContaminant($request);
            }
            else{
                $response = app()->handle($request); 
            }
            $response_json = json_encode($response); 
            $result = json_decode($response_json, true);
            array_push($response_array,$response->status());

        }
        echo json_encode($response_array);
    }

    public function getAirStationsData($isCron=false) 
    {
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "api/action/datastore_search?resource_id=d66f3e5d-8358-4704-8f52-00cb3d413938";

        $response_data = [];
        
        $response = ApiController::callAPI($base_uri, $uri,'');
        $pages = 0;
        if($response['success']){
            while(!empty($response['result']['records'])){
                $result = $response['result']; //Array
                $fields = $result['fields']; //Array
                $records = $result['records']; //Array
                $nextUri = $result['_links']['next'];//String
                $response_data[$pages]=$records;
                ApiController::processAirStationsData($records,$isCron);
                $response = ApiController::callAPI($base_uri,  substr($nextUri, 1),'');
                $pages++;
            }     
        }
    }

    private function processAirStationsData($data, $isCron){
        $response_array = [];

        foreach ($data as $estacio){
            $id = intval($estacio['Estacio']);
            $nom_estacio = $estacio['nom_cabina'];
            $codi_estacio = $estacio['codi_dtes'];
            $codi_eoi = intval($estacio['codi_eoi']);
            $latitud = (double)$estacio['Latitud'];
            $longitud = (double)$estacio['Longitud'];
            $ubicacio = $estacio['ubicacio'];
            $codi_districte = intval($estacio['Codi_districte']);
            $nom_districte = $estacio['Nom_districte'];
            $codi_barri = intval($estacio['Codi_barri']);
            $nom_barri = $estacio['Nom_barri'];
            $tipus_estacio_1 = $estacio['Clas_1'];
            $tipus_estacio_2 = $estacio['Clas_2'];
            $contaminant_id = intval($estacio['Codi_Contaminant']);

            $request_type=ApiController::getRequestType('/api/estacions/'.$id);
            $endpoint = $request_type == 'POST'?'/api/estacions':'/api/estacions/'.$id;
            $request = Request::create($endpoint, $request_type);
            $request->request->add([
                'id' => $id,
                'nom_estacio' => $nom_estacio,
                'codi_estacio' => $codi_estacio,
                'codi_eoi' => $codi_eoi,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'ubicacio' => $ubicacio,
                'codi_districte' => $codi_districte,
                'nom_districte' => $nom_districte,
                'codi_barri' => $codi_barri,
                'nom_barri' => $nom_barri,
                'tipus_estacio_1' => $tipus_estacio_1,
                'tipus_estacio_2' => $tipus_estacio_2,
                'contaminant_id' => $contaminant_id

            ]);
            if($isCron){
                $response = $request_type=='PUT'? EstacioController::updateEstacio($id,  $request):  EstacioController::createEstacio( $request);
            }
            else{
                $response = app()->handle($request); 
            }
            $response_json = json_encode($response); 
            $result = json_decode($response_json, true); 
            array_push($response_array,$response->status());
        }
        echo json_encode($response_array);
    }

    public function getTrafficData($isCron=false) 
    {
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "dataset/8319c2b1-4c21-4962-9acd-6db4c5ff1148/resource/2d456eb5-4ea6-4f68-9794-2f3f1a58a933/download";

        $response_data = [];

        $response_data = ApiController::callAPI($base_uri, $uri,'data');
        ApiController::processTrafficData($response_data,$isCron);
    }

    public function getTrafficData2($isCron=false) 
    {   
        for ($i = 401;$i<495;$i++ ){ 
            $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
            $uri = 'api/action/datastore_search?q=%7B"idTram"%3A"%25'.$i.'%25"%7D&limit=1000&resource_id=f9875fd1-0b07-49e7-aae9-eab3a230d450';

            $response_data = [];

            $response = ApiController::callAPI($base_uri, $uri,'');
            $pages = 0;
            if($response['success']){
                while(!empty($response['result']['records'])){
                    $result = $response['result']; 
                    $fields = $result['fields']; 
                    $records = $result['records']; 
                    $nextUri = $result['_links']['next'];
                    set_time_limit(300);
                    ApiController::processTrafficData($records,$isCron);
                    $response = ApiController::callAPI($base_uri,  substr($nextUri, 1),'');
                    $pages++;
                }
            }
        }
    }

    private function processTrafficData($data, $isCron){
        $response_array = [];
        set_time_limit(300);
        foreach ($data as $estat){
            $tram_id = ($estat['idTram']);
            $data = ($estat['data']);
            $estat_actual = intval($estat['estatActual']);
            $estat_previst = intval($estat['estatPrevist']);
            $id = $data;
            $id .= '_';
            $id .= $tram_id;
            $any = intval(substr($data,0,4)); 
            $mes = intval(substr($data,4,2));
            $dia = intval(substr($data,6,2));
            $hora= intval(substr($data,8,2));
            $minuts = intval(substr($data, 10,2));
            $segons = intval(substr($data, 12,2));
            if($segons > 30){ $minuts++;}
            $tram_id = intval($estat['idTram']);
            $data = intval($data);
            $request_type=ApiController::getRequestType('/api/estats/'.$id);
            $endpoint = $request_type == 'POST'?'/api/estats':'/api/estats/'.$id;
            $request = Request::create($endpoint, $request_type);
            $request->request->add([
                'id' => $id,
                'tram_id' => $tram_id,
                'data' => $data,
                'any' => $any,
                'mes' => $mes,
                'dia' => $dia,
                'hora' => $hora,
                'minuts' => $minuts,
                'estat_actual' => $estat_actual,
                'estat_previst' => $estat_previst
            ]);
            if(!empty(DataController::getResponse(TramController::showOneTram($tram_id)))){
                if($isCron){
                    $response = $request_type=='PUT'? EstatController::updateEstat($id,  $request):  EstatController::createEstat($request);
                }
                else{
                    $response = app()->handle($request); 
                }
                $response_json = json_encode($response);
                $result = json_decode($response_json, true); 
                array_push($response_array,$response->status());
            }
        }
        echo json_encode($response_array);
    }

    public function getSectionsData($isCron = false) 
    {
        $base_uri = "https://opendata-ajuntament.barcelona.cat/data/";
        $uri = "api/action/datastore_search?resource_id=1d6c814c-70ef-4147-aa16-a49ddb952f72";

        $response_data = [];
        
        $response = ApiController::callAPI($base_uri, $uri,'');
        $pages = 0;
        if($response['success']){
            while(!empty($response['result']['records'])){
                $result = $response['result']; //Array
                $fields = $result['fields']; //Array
                $records = $result['records']; //Array
                $nextUri = $result['_links']['next'];//String
                $response_data[$pages]=$records;
                ApiController::processSectionsData($records, $isCron);
                $response = ApiController::callAPI($base_uri,  substr($nextUri, 1),'');
                $pages++;
               
            }
        }
    }

    private function processSectionsData($data, $isCron){
        $response_array = [];

        foreach ($data as $estacio){
            $id = intval($estacio['Tram']);
            $descripcio = $estacio['Descripció'];
            $coordenades = $estacio['Coordenades'];
            $request_type=ApiController::getRequestType('/api/trams/'.$id);
            $endpoint = $request_type == 'POST'?'/api/trams':'/api/trams/'.$id;
            $request = Request::create($endpoint, $request_type); 
            $request->request->add([
                'id' => $id,
                'descripcio' => $descripcio,
                'coordenades' => $coordenades
            ]);
            if($isCron){
                $response = $request_type=='PUT'? TramController::updateTram($id,  $request):  TramController::createTram( $request);
            }
            else{
                $response = app()->handle($request); 
            }
            
            $response_json = json_encode($response); 
            $result = json_decode($response_json, true); 
            array_push($response_array,$response->status());
        }
        echo json_encode($response_array);
    }

    private function csvToJson($body) {
        $rows = explode("\n", trim($body));
        $data = array_slice($rows, 1);
        $keys = array_fill(0, count($data), $rows[0]);
        $json = array_map(function ($row, $key) {
            return array_combine(str_getcsv($key), str_getcsv($row));
        }, $data, $keys);

        return $json; 
    }

    private function dataToJson($body) {
        $attributes = "idTram,data,estatActual,estatPrevist";
        $rows = explode("\n", trim($body));
        //dd($rows);
        $data = $rows;
        $keys = array_fill(0, count($data),  $attributes);
        $json = array_map(function ($row, $key) {
            return array_combine(str_getcsv($key), str_getcsv($row,"#"));
        }, $data, $keys);
        return $json; //array
    }

    public static function getRequestType($endpoint){

        $request = Request::create($endpoint, 'GET'); 
        //dd($request);
        $response = app()->handle($request); 
        $response_json = json_encode($response); 
        $result = json_decode($response_json, true); 
        //dd((empty($result['original'])));
        if (!empty($result['original'])){
            return 'PUT';
        }else{
            return 'POST';
        }
        
    }


}
