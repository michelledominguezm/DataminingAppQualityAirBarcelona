<?php

namespace App\Http\Controllers;
use App\Models\Resultat;
use App\Models\Contaminant;
use App\Models\Estacio;
use App\Models\Indicador;
use Illuminate\Http\Request;

class ResultatController extends Controller
{
    //CRUD OPERATIONS

    //Create
    public static function createResultat(Request $request)
    {
        $contaminant_id = $request->only('contaminant_id');
        $estacio_id = $request->only('estacio_id');
        $indicador_id = $request->only('indicador_id');
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id)->first();
            $estacio = Estacio::findOrFail($estacio_id)->first();
            $indicador = Estacio::findOrFail($estacio_id)->first();
        }catch (ModelNotFoundException $e) {
            return response('Contaminant, Estacio or Indicador not found', 404);
        }

        $resultat = Resultat::create($request->all());
        return response()->json($resultat, 201);
    }

    //Read all
    public static function  showAllResultats(Request $request)
    {
        $resultats=Resultat::all();
        if(!empty($request->query())){
            $query = $request->except(['data_inici','data_final']);
            foreach ($query as $name => $value){
                $condition = [$name, $value];
                
                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }
        return response()->json($resultats);
    }

    //Read one
    public static function  showOneResultat($resultat_id)
    {
        return response()->json(Resultat::find($resultat_id));
    }

    public static function  showContaminantFromResultat($resultat_id){
        try{
            $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
        $contaminant = $resultat->contaminant;

        return response()->json($contaminant, 200);
    }

    public static function  showEstacioFromResultat($resultat_id){
        try{
            $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
        $estacio = $resultat->estacio;

        return response()->json($estacio, 200);
    }

    public static function  showIndicadorFromResultat($resultat_id){
        try{
            $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
        $indicador = $resultat->indicador;

        return response()->json($indicador, 200);
    }

    public static function showMostraFromResultat($resultat_id){
        try{
            $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
        $mostra = $resultat->mostra;

        return response()->json($mostra, 200);
    }

    public static function  showImmissioFromResultat($resultat_id){
        try{
            $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
        $immissio = $resultat->immissio;

        return response()->json($immissio, 200);
    }

    //Update
    public static function  updateResultat($resultat_id, Request $request)
    {
        try{
             $resultat = Resultat::findOrFail($resultat_id);
        }catch (ModelNotFoundException $e) {
            return response('Resultat not found', 404);    
        }
       
        $resultat->update($request->except(['id','estacio_id','contaminant_id','indicador_id']));
        return response()->json($resultat, 200);
    }

    //Delete
    public static function  deleteResultat($resultat_id)
    {
        Resultat::findOrFail($resultat_id)->delete();
        return response('Deleted Successfully', 200);
    }

    //Get
    public static function  showResultatsFromPeriode(Request $request)
    {
        //Needs startDate and endDate
        $start_date = explode("T", $request->only('start_date')['start_date'])[0];
        $end_date = explode("T",$request->only('end_date')['end_date'])[0];
        $start_date_day = (int)explode("-",$start_date)[2];
        $end_date_day = (int)explode("-",$end_date)[2];
        $start_date_month =  (int)explode("-",$start_date)[1];
        $end_date_month =  (int)explode("-",$end_date)[1];
        $start_date_year =  (int)explode("-",$start_date)[0];
        $end_date_year =  (int)explode("-",$end_date)[0];

        if ( $start_date_month==$end_date_month && $start_date_year==$end_date_year){
            $resultats_start = $resultats_end = $resultats = Resultat::whereBetween('dia',[$start_date_day,$end_date_day])->whereBetween('mes',[$start_date_month,$end_date_month])->whereBetween('any', [$start_date_year, $end_date_year])//where('dia','>=',$start_date_day)->where('mes','>=',$start_date_month)->where('any','>=', $start_date_year)
            ->orderBy('any','ASC')
            ->orderBy('mes','ASC')
            ->get();
        }else{
            if($start_date_year==$end_date_year){
                $resultats_start = Resultat::where('dia','>=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=', $start_date_year)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');

                $resultats_middle = ($end_date_month-$start_date_month)>1? Resultat::where('any','=', $start_date_year)->whereBetween('mes',[$start_date_month+1,$end_date_month-1])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC')
                :Resultat::where('any','=',2222);

                $resultats_end = Resultat::where('dia','<=',$end_date_day)->where('mes','=',$end_date_month)->where('any','=',$end_date_year)
                ->union($resultats_middle)
                ->union($resultats_start)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC')
                ->get();
                $resultats=($resultats_end);
            }else{
                $resultats_start = Resultat::where('dia','>=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=', $start_date_year)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');

                $resultats_middle_start_year = Resultat::where('any','=', $start_date_year)->whereBetween('mes',[$start_date_month+1,12])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');

                $resultats_middle_end_year = Resultat::where('any','=', $start_date_year)->whereBetween('mes',[1,$end_date_month-1])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');
       
                $resultats_end = Resultat::where('dia','<=',$end_date_day)->where('mes','=',$end_date_month)->where('any','=',$end_date_year)

                ->union($resultats_middle_start_year)
                ->union($resultats_middle_end_year)
                ->union($resultats_start)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC')
                ->get();
                $resultats=($resultats_end);
            }
        }
        if(!empty($request->query())){
        $query = $request->except(['start_date','end_date']);
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }
        $resultats = ResultatController::createGeneralResultat($resultats);
        return response()->json($resultats, 200);
    }

    public static function  showResultatsFromDies(Request $request)
    {
        //Needs 2 dates
        $start_date = explode("T", $request->only('start_date')['start_date'])[0];
        $end_date = explode("T",$request->only('end_date')['end_date'])[0];
        $start_date_day = explode("-",$start_date)[2];
        $end_date_day = explode("-",$end_date)[2];
        $start_date_month =  explode("-",$start_date)[1];
        $end_date_month =  explode("-",$end_date)[1];
        $start_date_year =  explode("-",$start_date)[0];
        $end_date_year =  explode("-",$end_date)[0];

        $resultats = Resultat::where('dia','=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=',  $start_date_year)
             ->orWhere('dia','=',$end_date_day)->where('mes','=',$end_date_month)->where('any','=',$end_date_year)
             ->orderBy('any','ASC')
             ->orderBy('mes','ASC')
             ->orderBy('dia','ASC')
             ->get();
        if(!empty($request->query())){
            $query = $request->except(['start_date','end_date']);
            foreach ($query as $name => $value){
                $condition = [$name, $value];
                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }
        $resultats = ResultatController::createGeneralResultat($resultats);
        return response()->json($resultats, 200);
    }

    private static function createGeneralResultat($resultats){
        $mitjana_general = null;
        $array_resultats = array();

        foreach ($resultats as $res){
            $resultat = $res->toArray();
            $any=$resultat['any'];
            $mes=$resultat['mes'];
            $dia=$resultat['dia'];
            $date=date_create($any."-".$mes."-".$dia);
            $formatted_date = $date->format('Y-n-j');     
            $mitjana = $resultat['mitjana'];
            $qualificacio = $resultat['qualificacio'];
            $H01 = $resultat['H01'];
            $H02 = $resultat['H02'];
            $H03 = $resultat['H03'];
            $H04 = $resultat['H04'];
            $H05 = $resultat['H05'];
            $H06 = $resultat['H06'];
            $H07 = $resultat['H07'];
            $H08 = $resultat['H08'];
            $H09 = $resultat['H09'];
            $H10 = $resultat['H10'];
            $H11 = $resultat['H11'];
            $H12 = $resultat['H12'];
            $H13 = $resultat['H13'];
            $H14 = $resultat['H14'];
            $H15 = $resultat['H15'];
            $H16 = $resultat['H16'];
            $H17 = $resultat['H17'];
            $H18 = $resultat['H18'];
            $H19 = $resultat['H19'];
            $H20 = $resultat['H20'];
            $H21 = $resultat['H21'];
            $H22 = $resultat['H22'];
            $H23 = $resultat['H23'];
            $H24 = $resultat['H24'];
            $R01 = $resultat['R01'];
            $R02 = $resultat['R02'];
            $R03 = $resultat['R03'];
            $R04 = $resultat['R04'];
            $R05 = $resultat['R05'];
            $R06 = $resultat['R06'];
            $R07 = $resultat['R07'];
            $R08 = $resultat['R08'];
            $R09 = $resultat['R09'];
            $R10 = $resultat['R10'];
            $R11 = $resultat['R11'];
            $R12 = $resultat['R12'];
            $R13 = $resultat['R13'];
            $R14 = $resultat['R14'];
            $R15 = $resultat['R15'];
            $R16 = $resultat['R16'];
            $R17 = $resultat['R17'];
            $R18 = $resultat['R18'];
            $R19 = $resultat['R19'];
            $R20 = $resultat['R20'];
            $R21 = $resultat['R21'];
            $R22 = $resultat['R22'];
            $R23 = $resultat['R23'];
            $R24 = $resultat['R24'];
           
            $keys = array_keys($array_resultats);
            $index = array_search($formatted_date, $keys);
            
            if(($index) === false){
                $array_resultats[$formatted_date] = array("Data"=>$formatted_date,"Mitjana"=>$mitjana, "MitjanaAcumulable"=>$mitjana,"Qualificacio"=>$qualificacio, "Comptador"=>1, "Hores" => ['H01' => $H01,'R01' => $R01, 'H02' =>$H02,'R02' => $R02,'H03' => $H03,'R03' => $R03,'H04' => $H04,'R04' => $R04,'H05' => $H05,'R05' => $R05,'H06' => $H06,'R06' => $R06,'H07' => $H07,'R07' => $R07,'H08' => $H08,'R08' => $R08,'H09' => $H09,'R09' => $R09,'H10' => $H10,'R10' => $R10,'H11' => $H11,'R11' => $R11,'H12' => $H12,'R12' => $R12,'H13' => $H13,'R13' => $R13,'H14' => $H14,'R14' => $R14,'H15' => $H15,'R15' => $R15,'H16' => $H16,'R16' => $R16,'H17' => $H17,'R17' => $R17,'H18' => $H18,'R18' => $R18,'H19' => $H19,'R19' => $R19,'H20' => $H20,'R20' => $R20,'H21' => $H21,'R21' => $R21,'H22' => $H22,'R22' => $R22,'H23' => $H23,'R23' => $R23,'H24' => $H24, 'R24' => $R24]);
            }else{
                $current = $array_resultats[$keys[$index]];
                $current['MitjanaAcumulable']+=$mitjana;            
                $current['Qualificacio'].=  (",".$qualificacio);
                $current['Comptador']+=1;
                $current['Mitjana']=(float)$current['MitjanaAcumulable']/$current['Comptador'];
                $current['Hores']['H01'] += $H01;
                $current['Hores']['R01'] .= (",".$R01);
                $current['Hores']['H02'] += $H02;
                $current['Hores']['R02'] .= (",".$R02);
                $current['Hores']['H03'] += $H03;
                $current['Hores']['R03'] .= (",".$R03);
                $current['Hores']['H04'] += $H04;
                $current['Hores']['R04'] .= (",".$R04);
                $current['Hores']['H05'] += $H05;
                $current['Hores']['R05'] .= (",".$R05);
                $current['Hores']['H06'] += $H06;
                $current['Hores']['R06'] .= (",".$R06);
                $current['Hores']['H07'] += $H07;
                $current['Hores']['R07'] .= (",".$R07);
                $current['Hores']['H08'] += $H08;
                $current['Hores']['R08'] .= (",".$R08);
                $current['Hores']['H09'] += $H09;
                $current['Hores']['R09'] .= (",".$R09);
                $current['Hores']['H10'] += $H10;
                $current['Hores']['R10'] .= (",".$R10);
                $current['Hores']['H11'] += $H11;
                $current['Hores']['R11'] .= (",".$R11);
                $current['Hores']['H12'] += $H12;
                $current['Hores']['R12'] .= (",".$R12);
                $current['Hores']['H13'] += $H13;
                $current['Hores']['R13'] .= (",".$R13);
                $current['Hores']['H14'] += $H14;
                $current['Hores']['R14'] .= (",".$R14);
                $current['Hores']['H15'] += $H15;
                $current['Hores']['R15'] .= (",".$R15);
                $current['Hores']['H16'] += $H16;
                $current['Hores']['R16'] .= (",".$R16);
                $current['Hores']['H17'] += $H17;
                $current['Hores']['R17'] .= (",".$R17);
                $current['Hores']['H18'] += $H18;
                $current['Hores']['R18'] .= (",".$R18);
                $current['Hores']['H19'] += $H19;
                $current['Hores']['R19'] .= (",".$R19);
                $current['Hores']['H20'] += $H20;
                $current['Hores']['R20'] .= (",".$R20);
                $current['Hores']['H21'] += $H21;
                $current['Hores']['R21'] .= (",".$R21);
                $current['Hores']['H22'] += $H22;
                $current['Hores']['R22'] .= (",".$R22);
                $current['Hores']['H23'] += $H23;
                $current['Hores']['R23'] .= (",".$R23);
                $current['Hores']['H24'] += $H24;
                $current['Hores']['R24'] .= (",".$R24);
                $array_resultats[$keys[$index]] = $current;
            }
            
        }
        $keys = array_keys($array_resultats);
       
        foreach ($array_resultats as $resposta){
            $index = array_search( $resposta['Data'], $keys);
            $resposta['Qualificacio'] = ResultatController::getQualificacio($resposta['Qualificacio']);
            $resposta['Hores']['H01'] = $resposta['Hores']['H01']==null?null:(float)$resposta['Hores']['H01']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R01']);
            $resposta['Hores']['R01'] = $resposta['Hores']['H01']==null?null:ResultatController::getQualificacio($resposta['Hores']['R01']);
            $resposta['Hores']['H02'] = $resposta['Hores']['H02']==null?null:(float)$resposta['Hores']['H02']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R02']);
            $resposta['Hores']['R02'] = $resposta['Hores']['H02']==null?null:ResultatController::getQualificacio($resposta['Hores']['R02']);
            $resposta['Hores']['H03'] = $resposta['Hores']['H03']==null?null:(float)$resposta['Hores']['H03']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R03']);
            $resposta['Hores']['R03'] = $resposta['Hores']['H03']==null?null:ResultatController::getQualificacio($resposta['Hores']['R03']);
            $resposta['Hores']['H04'] = $resposta['Hores']['H04']==null?null:(float)$resposta['Hores']['H04']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R04']);
            $resposta['Hores']['R04'] = $resposta['Hores']['H04']==null?null:ResultatController::getQualificacio($resposta['Hores']['R04']);
            $resposta['Hores']['H05'] = $resposta['Hores']['H05']==null?null:(float)$resposta['Hores']['H05']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R05']);
            $resposta['Hores']['R05'] = $resposta['Hores']['H05']==null?null:ResultatController::getQualificacio($resposta['Hores']['R05']);
            $resposta['Hores']['H06'] = $resposta['Hores']['H06']==null?null:(float)$resposta['Hores']['H06']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R06']);
            $resposta['Hores']['R06'] = $resposta['Hores']['H06']==null?null:ResultatController::getQualificacio($resposta['Hores']['R06']);
            $resposta['Hores']['H07'] = $resposta['Hores']['H07']==null?null:(float)$resposta['Hores']['H07']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R07']);
            $resposta['Hores']['R07'] = $resposta['Hores']['H07']==null?null:ResultatController::getQualificacio($resposta['Hores']['R07']);
            $resposta['Hores']['H08'] = $resposta['Hores']['H08']==null?null:(float)$resposta['Hores']['H08']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R08']);
            $resposta['Hores']['R08'] = $resposta['Hores']['H08']==null?null:ResultatController::getQualificacio($resposta['Hores']['R08']);
            $resposta['Hores']['H09'] = $resposta['Hores']['H09']==null?null:(float)$resposta['Hores']['H09']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R09']);
            $resposta['Hores']['R09'] = $resposta['Hores']['H09']==null?null:ResultatController::getQualificacio($resposta['Hores']['R09']);
            $resposta['Hores']['H10'] = $resposta['Hores']['H10']==null?null:(float)$resposta['Hores']['H10']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R10']);
            $resposta['Hores']['R10'] = $resposta['Hores']['H10']==null?null:ResultatController::getQualificacio($resposta['Hores']['R10']);
            $resposta['Hores']['H11'] = $resposta['Hores']['H11']==null?null:(float)$resposta['Hores']['H11']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R11']);
            $resposta['Hores']['R11'] = $resposta['Hores']['H11']==null?null:ResultatController::getQualificacio($resposta['Hores']['R11']);
            $resposta['Hores']['H12'] = $resposta['Hores']['H12']==null?null:(float)$resposta['Hores']['H12']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R12']);
            $resposta['Hores']['R12'] = $resposta['Hores']['H12']==null?null:ResultatController::getQualificacio($resposta['Hores']['R12']);
            $resposta['Hores']['H13'] = $resposta['Hores']['H13']==null?null:(float)$resposta['Hores']['H13']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R13']);
            $resposta['Hores']['R13'] = $resposta['Hores']['H13']==null?null:ResultatController::getQualificacio($resposta['Hores']['R13']);
            $resposta['Hores']['H14'] = $resposta['Hores']['H14']==null?null:(float)$resposta['Hores']['H14']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R14']);
            $resposta['Hores']['R14'] = $resposta['Hores']['H14']==null?null:ResultatController::getQualificacio($resposta['Hores']['R14']);
            $resposta['Hores']['H15'] = $resposta['Hores']['H15']==null?null:(float)$resposta['Hores']['H15']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R15']);
            $resposta['Hores']['R15'] = $resposta['Hores']['H15']==null?null:ResultatController::getQualificacio($resposta['Hores']['R15']);
            $resposta['Hores']['H16'] = $resposta['Hores']['H16']==null?null:(float)$resposta['Hores']['H16']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R16']);
            $resposta['Hores']['R16'] = $resposta['Hores']['H16']==null?null:ResultatController::getQualificacio($resposta['Hores']['R16']);
            $resposta['Hores']['H17'] = $resposta['Hores']['H17']==null?null:(float)$resposta['Hores']['H17']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R17']);
            $resposta['Hores']['R17'] = $resposta['Hores']['H17']==null?null:ResultatController::getQualificacio($resposta['Hores']['R17']);
            $resposta['Hores']['H18'] = $resposta['Hores']['H18']==null?null:(float)$resposta['Hores']['H18']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R18']);
            $resposta['Hores']['R18'] = $resposta['Hores']['H18']==null?null:ResultatController::getQualificacio($resposta['Hores']['R18']);
            $resposta['Hores']['H19'] = $resposta['Hores']['H19']==null?null:(float)$resposta['Hores']['H19']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R19']);
            $resposta['Hores']['R19'] = $resposta['Hores']['H19']==null?null:ResultatController::getQualificacio($resposta['Hores']['R19']);
            $resposta['Hores']['H20'] = $resposta['Hores']['H20']==null?null:(float)$resposta['Hores']['H20']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R20']);
            $resposta['Hores']['R20'] = $resposta['Hores']['H20']==null?null:ResultatController::getQualificacio($resposta['Hores']['R20']);
            $resposta['Hores']['H21'] = $resposta['Hores']['H21']==null?null:(float)$resposta['Hores']['H21']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R21']);
            $resposta['Hores']['R21'] = $resposta['Hores']['H21']==null?null:ResultatController::getQualificacio($resposta['Hores']['R21']);
            $resposta['Hores']['H22'] = $resposta['Hores']['H22']==null?null:(float)$resposta['Hores']['H22']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R22']);
            $resposta['Hores']['R22'] = $resposta['Hores']['H22']==null?null:ResultatController::getQualificacio($resposta['Hores']['R22']);
            $resposta['Hores']['H23'] = $resposta['Hores']['H23']==null?null:(float)$resposta['Hores']['H23']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R23']);
            $resposta['Hores']['R23'] = $resposta['Hores']['H23']==null?null:ResultatController::getQualificacio($resposta['Hores']['R23']);
            $resposta['Hores']['H24'] = $resposta['Hores']['H24']==null?null:(float)$resposta['Hores']['H24']/ResultatController::getNumberOfQualificacions($resposta['Hores']['R24']);
            $resposta['Hores']['R24'] = $resposta['Hores']['H24']==null?null:ResultatController::getQualificacio($resposta['Hores']['R24']);
            $array_resultats[$keys[$index]] = $resposta;
        }
        return $array_resultats;
    }

    private static function getQualificacio($qualificacions){
        $bo_count=substr_count($qualificacions,"bo");
        $moderat_count=substr_count($qualificacions,"moderat");
        $regular_count=substr_count($qualificacions,"regular");
        $dolent_count=substr_count($qualificacions,"dolent");
        $moltdolent_count=substr_count($qualificacions,"molt dolent")+substr_count($qualificacions,"molt_dolent");

        if($bo_count>$moderat_count && $bo_count>$regular_count && $bo_count>$dolent_count && $bo_count>$moltdolent_count){
            return "bo";
        }else if($moderat_count>$regular_count && $moderat_count>$dolent_count && $moderat_count>$moltdolent_count){
            return "moderat";
        }else if($regular_count>$dolent_count && $regular_count>$moltdolent_count){
            return "regular";
        }else if($regular_count>$moltdolent_count){
            return "dolent";
        }else{
            return "molt dolent";
        }
    }

    private static function getNumberOfQualificacions($qualificacions){
        $q = str_replace("molt dolent","moltdolent",$qualificacions);
        return str_word_count($q)==0?1:str_word_count($q);
    }



}
