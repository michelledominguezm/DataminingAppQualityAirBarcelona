<?php

namespace App\Http\Controllers;
use App\Models\Estat;
use App\Models\Tram;
use App\Models\Congestio;
use Illuminate\Http\Request;

class CongestioController extends Controller
{
    //CRUD OPERATIONS

    //Create
    public static function createCongestio(Request $request)
    {
        set_time_limit(14400);
        $tram_id = $request->only('tram_id');

        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);
        }
        $congestio = Congestio::create($request->all());

        return response()->json($congestio, 201);
    }

    //Read all
    public static function  showAllCongestions(Request $request)
    {
        $resultats = Congestio::all();
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
    public static function  showOneCongestio($congestio_id)
    {
        return response()->json(Congestio::find($congestio_id));
    }
    
    public static function  showTramFromCongestio($congestio_id){
        try{
            $congestio = Congestio::findOrFail($congestio_id);
        }catch (ModelNotFoundException $e) {
            return response('Congestio not found', 404);    
        }
        $tram = $congestio->tram;

        return response()->json($tram, 200);
    }

    public static function  showAllEstatsFromCongestio($congestio_id){
        try{
            $congestio = Congestio::findOrFail($congestio_id);
        }catch (ModelNotFoundException $e) {
            return response('Congestio not found', 404);    
        }
        $estats = $congestio->estats;

        return response()->json($congestio, 200);
    }

    public static function showEstatFromCongestio($congestio_id, $estat_id)
    {
        try{
            $congestio = Congestio::findOrFail($congestio_id);
        }catch (ModelNotFoundException $e) {
            return response('Congestio not found', 404);    
        }
        $estat = $congestio->estats
                       ->where('id', '=', $estat_id)
                       ->first();
        return response()->json($estat, 200);
    }

    //Update
    public static function  updateCongestio($congestio_id, Request $request)
    {
        set_time_limit(14400);
        try{
            $congestio = Congestio::findOrFail($congestio_id);
        }catch (ModelNotFoundException $e) {
            return response('Congestio not found', 404);    
        }
        
        $congestio->update($request->except(['id','tram_id']));
        return response()->json($congestio, 200);
    }

    //Delete
    public static function  deleteCongestio($congestio_id)
    {
        Congestio::findOrFail($congestio_id)->delete();
        return response('Deleted Successfully', 200);
    }

    //Get
    public static function  showCongestionsFromPeriode(Request $request)
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
            $resultats_start = $resultats_end = $resultats = Congestio::whereBetween('dia',[$start_date_day,$end_date_day])->whereBetween('mes',[$start_date_month,$end_date_month])->whereBetween('any', [$start_date_year, $end_date_year])//where('dia','>=',$start_date_day)->where('mes','>=',$start_date_month)->where('any','>=', $start_date_year)
            ->orderBy('any','ASC')
            ->orderBy('mes','ASC')
            ->get();
        }else{
            if($start_date_year==$end_date_year){
                $resultats_start = Congestio::where('dia','>=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=', $start_date_year)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');
                
                $resultats_middle = ($end_date_month-$start_date_month)>1? Congestio::where('any','=', $start_date_year)->whereBetween('mes',[$start_date_month+1,$end_date_month-1])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC')
                :Congestio::where('any','=',2222);
                      
                $resultats_end = Congestio::where('dia','<=',$end_date_day)->where('mes','=',$end_date_month)->where('any','=',$end_date_year)
                ->union($resultats_middle)
                ->union($resultats_start)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC')
                ->get();
                $resultats=($resultats_end);
            }else{
                $resultats_start = Congestio::where('dia','>=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=', $start_date_year)
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');
                
                $resultats_middle_start_year = Congestio::where('any','=', $start_date_year)->whereBetween('mes',[$start_date_month+1,12])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');
               
                $resultats_middle_end_year = Congestio::where('any','=', $start_date_year)->whereBetween('mes',[1,$end_date_month-1])
                ->orderBy('any','ASC')
                ->orderBy('mes','ASC')
                ->orderBy('dia','ASC');
                       
                $resultats_end = Congestio::where('dia','<=',$end_date_day)->where('mes','=',$end_date_month)->where('any','=',$end_date_year)
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
        $resultats = CongestioController::createGeneralResultat($resultats);
        return response()->json($resultats, 200);
    }

    public static function  showCongestionsFromDies(Request $request)
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

        $resultats = Congestio::where('dia','=',$start_date_day)->where('mes','=',$start_date_month)->where('any','=',  $start_date_year)
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
        $resultats = CongestioController::createGeneralResultat($resultats);
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
            $actual = $resultat['actual'];
            $previst = $resultat['previst'];
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
            $P01 = $resultat['P01'];
            $P02 = $resultat['P02'];
            $P03 = $resultat['P03'];
            $P04 = $resultat['P04'];
            $P05 = $resultat['P05'];
            $P06 = $resultat['P06'];
            $P07 = $resultat['P07'];
            $P08 = $resultat['P08'];
            $P09 = $resultat['P09'];
            $P10 = $resultat['P10'];
            $P11 = $resultat['P11'];
            $P12 = $resultat['P12'];
            $P13 = $resultat['P13'];
            $P14 = $resultat['P14'];
            $P15 = $resultat['P15'];
            $P16 = $resultat['P16'];
            $P17 = $resultat['P17'];
            $P18 = $resultat['P18'];
            $P19 = $resultat['P19'];
            $P20 = $resultat['P20'];
            $P21 = $resultat['P21'];
            $P22 = $resultat['P22'];
            $P23 = $resultat['P23'];
            $P24 = $resultat['P24'];
           
            $keys = array_keys($array_resultats);
            $index = array_search($formatted_date, $keys);
            
            if(($index) === false){
                $array_resultats[$formatted_date] = array("Data"=>$formatted_date,"Actual"=>$actual, "ActualAcumulable"=>$actual,"Previst"=>$previst, "PrevistAcumulable"=>$previst,"Comptador"=>1, "Hores" => ['H01' => $H01,'P01' => $P01, 'H02' =>$H02,'P02' => $P02,'H03' => $H03,'P03' => $P03,'H04' => $H04,'P04' => $P04,'H05' => $H05,'P05' => $P05,'H06' => $H06,'P06' => $P06,'H07' => $H07,'P07' => $P07,'H08' => $H08,'P08' => $P08,'H09' => $H09,'P09' => $P09,'H10' => $H10,'P10' => $P10,'H11' => $H11,'P11' => $P11,'H12' => $H12,'P12' => $P12,'H13' => $H13,'P13' => $P13,'H14' => $H14,'P14' => $P14,'H15' => $H15,'P15' => $P15,'H16' => $H16,'P16' => $P16,'H17' => $H17,'P17' => $P17,'H18' => $H18,'P18' => $P18,'H19' => $H19,'P19' => $P19,'H20' => $H20,'P20' => $P20,'H21' => $H21,'P21' => $P21,'H22' => $H22,'P22' => $P22,'H23' => $H23,'P23' => $P23,'H24' => $H24, 'P24' => $P24]);
            }else{
            $current = $array_resultats[$keys[$index]];
            $current['ActualAcumulable']+=$actual;            
            $current['PrevistAcumulable']+=$previst;
            $current['Comptador']+=1;
            $current['Actual']=(float)$current['ActualAcumulable']/$current['Comptador'];
            $current['Previst']=(float)$current['PrevistAcumulable']/$current['Comptador'];
            $current['Hores']['H01'] += ($H01==null?0:$H01);
            $current['Hores']['P01'] +=($P01==null?0:$P01);
            $current['Hores']['H02'] += ($H02==null?0:$H02);
            $current['Hores']['P02'] +=($P02==null?0:$P02);
            $current['Hores']['H03'] += ($H03==null?0:$H03);
            $current['Hores']['P03'] +=($P03==null?0:$P03);
            $current['Hores']['H04'] += ($H04==null?0:$H04);
            $current['Hores']['P04'] +=($P04==null?0:$P04);
            $current['Hores']['H05'] += ($H05==null?0:$H05);
            $current['Hores']['P05'] +=($P05==null?0:$P05);
            $current['Hores']['H06'] += ($H06==null?0:$H06);
            $current['Hores']['P06'] +=($P06==null?0:$P06);
            $current['Hores']['H07'] += ($H07==null?0:$H07);
            $current['Hores']['P07'] +=($P07==null?0:$P07);
            $current['Hores']['H08'] += ($H08==null?0:$H08);
            $current['Hores']['P08'] +=($P08==null?0:$P08);
            $current['Hores']['H09'] += ($H09==null?0:$H09);
            $current['Hores']['P09'] +=($P09==null?0:$P09);
            $current['Hores']['H10'] += ($H10==null?0:$H10);
            $current['Hores']['P10'] +=($P10==null?0:$P10);
            $current['Hores']['H11'] += ($H11==null?0:$H11);
            $current['Hores']['P11'] +=($P11==null?0:$P11);
            $current['Hores']['H12'] += ($H12==null?0:$H12);
            $current['Hores']['P12'] +=($P12==null?0:$P12);
            $current['Hores']['H13'] += ($H13==null?0:$H13);
            $current['Hores']['P13'] +=($P13==null?0:$P13);
            $current['Hores']['H14'] += ($H14==null?0:$H14);
            $current['Hores']['P14'] +=($P14==null?0:$P14);
            $current['Hores']['H15'] += ($H15==null?0:$H15);
            $current['Hores']['P15'] +=($P15==null?0:$P15);
            $current['Hores']['H16'] += ($H16==null?0:$H16);
            $current['Hores']['P16'] +=($P16==null?0:$P16);
            $current['Hores']['H17'] += ($H17==null?0:$H17);
            $current['Hores']['P17'] +=($P17==null?0:$P17);
            $current['Hores']['H18'] += ($H18==null?0:$H18);
            $current['Hores']['P18'] +=($P18==null?0:$P18);
            $current['Hores']['H19'] += ($H19==null?0:$H19);
            $current['Hores']['P19'] +=($P19==null?0:$P19);
            $current['Hores']['H20'] += ($H20==null?0:$H20);
            $current['Hores']['P20'] +=($P20==null?0:$P20);
            $current['Hores']['H21'] += ($H21==null?0:$H21);
            $current['Hores']['P21'] +=($P21==null?0:$P21);
            $current['Hores']['H22'] += ($H22==null?0:$H22);
            $current['Hores']['P22'] +=($P22==null?0:$P22);
            $current['Hores']['H23'] += ($H23==null?0:$H23);
            $current['Hores']['P23'] +=($P23==null?0:$P23);
            $current['Hores']['H24'] += ($H24==null?0:$H24);
            $current['Hores']['P24'] +=($P24==null?0:$P24);
            $array_resultats[$keys[$index]] = $current;
            }
            
        }
        $keys = array_keys($array_resultats);
       
        foreach ($array_resultats as $resposta){
            $index = array_search( $resposta['Data'], $keys);
            $quantitat = $resposta['Comptador'];
            $resposta['Actual'] = CongestioController::getQualificacio($resposta['Actual']);
            $resposta['Previst'] = CongestioController::getQualificacio($resposta['Previst']);
            $resposta['Hores']['H01'] = CongestioController::getQualificacio((float)$resposta['Hores']['H01']/$quantitat);
            $resposta['Hores']['P01'] = CongestioController::getQualificacio((float)$resposta['Hores']['P01']/$quantitat);
            $resposta['Hores']['H02'] = CongestioController::getQualificacio((float)$resposta['Hores']['H02']/$quantitat);
            $resposta['Hores']['P02'] = CongestioController::getQualificacio((float)$resposta['Hores']['P02']/$quantitat);
            $resposta['Hores']['H03'] = CongestioController::getQualificacio((float)$resposta['Hores']['H03']/$quantitat);
            $resposta['Hores']['P03'] = CongestioController::getQualificacio((float)$resposta['Hores']['P03']/$quantitat);
            $resposta['Hores']['H04'] = CongestioController::getQualificacio((float)$resposta['Hores']['H04']/$quantitat);
            $resposta['Hores']['P04'] = CongestioController::getQualificacio((float)$resposta['Hores']['P04']/$quantitat);
            $resposta['Hores']['H05'] = CongestioController::getQualificacio((float)$resposta['Hores']['H05']/$quantitat);
            $resposta['Hores']['P05'] = CongestioController::getQualificacio((float)$resposta['Hores']['P05']/$quantitat);
            $resposta['Hores']['H06'] = CongestioController::getQualificacio((float)$resposta['Hores']['H06']/$quantitat);
            $resposta['Hores']['P06'] = CongestioController::getQualificacio((float)$resposta['Hores']['P06']/$quantitat);
            $resposta['Hores']['H07'] = CongestioController::getQualificacio((float)$resposta['Hores']['H07']/$quantitat);
            $resposta['Hores']['P07'] = CongestioController::getQualificacio((float)$resposta['Hores']['P07']/$quantitat);
            $resposta['Hores']['H08'] = CongestioController::getQualificacio((float)$resposta['Hores']['H08']/$quantitat);
            $resposta['Hores']['P08'] = CongestioController::getQualificacio((float)$resposta['Hores']['P08']/$quantitat);
            $resposta['Hores']['H09'] = CongestioController::getQualificacio((float)$resposta['Hores']['H09']/$quantitat);
            $resposta['Hores']['P09'] = CongestioController::getQualificacio((float)$resposta['Hores']['P09']/$quantitat);
            $resposta['Hores']['H10'] = CongestioController::getQualificacio((float)$resposta['Hores']['H10']/$quantitat);
            $resposta['Hores']['P10'] = CongestioController::getQualificacio((float)$resposta['Hores']['P10']/$quantitat);
            $resposta['Hores']['H11'] = CongestioController::getQualificacio((float)$resposta['Hores']['H11']/$quantitat);
            $resposta['Hores']['P11'] = CongestioController::getQualificacio((float)$resposta['Hores']['P11']/$quantitat);
            $resposta['Hores']['H12'] = CongestioController::getQualificacio((float)$resposta['Hores']['H12']/$quantitat);
            $resposta['Hores']['P12'] = CongestioController::getQualificacio((float)$resposta['Hores']['P12']/$quantitat);
            $resposta['Hores']['H13'] = CongestioController::getQualificacio((float)$resposta['Hores']['H13']/$quantitat);
            $resposta['Hores']['P13'] = CongestioController::getQualificacio((float)$resposta['Hores']['P13']/$quantitat);
            $resposta['Hores']['H14'] = CongestioController::getQualificacio((float)$resposta['Hores']['H14']/$quantitat);
            $resposta['Hores']['P14'] = CongestioController::getQualificacio((float)$resposta['Hores']['P14']/$quantitat);
            $resposta['Hores']['H15'] = CongestioController::getQualificacio((float)$resposta['Hores']['H15']/$quantitat);
            $resposta['Hores']['P15'] = CongestioController::getQualificacio((float)$resposta['Hores']['P15']/$quantitat);
            $resposta['Hores']['H16'] = CongestioController::getQualificacio((float)$resposta['Hores']['H16']/$quantitat);
            $resposta['Hores']['P16'] = CongestioController::getQualificacio((float)$resposta['Hores']['P16']/$quantitat);
            $resposta['Hores']['H17'] = CongestioController::getQualificacio((float)$resposta['Hores']['H17']/$quantitat);
            $resposta['Hores']['P17'] = CongestioController::getQualificacio((float)$resposta['Hores']['P17']/$quantitat);
            $resposta['Hores']['H18'] = CongestioController::getQualificacio((float)$resposta['Hores']['H18']/$quantitat);
            $resposta['Hores']['P18'] = CongestioController::getQualificacio((float)$resposta['Hores']['P18']/$quantitat);
            $resposta['Hores']['H19'] = CongestioController::getQualificacio((float)$resposta['Hores']['H19']/$quantitat);
            $resposta['Hores']['P19'] = CongestioController::getQualificacio((float)$resposta['Hores']['P19']/$quantitat);
            $resposta['Hores']['H20'] = CongestioController::getQualificacio((float)$resposta['Hores']['H20']/$quantitat);
            $resposta['Hores']['P20'] = CongestioController::getQualificacio((float)$resposta['Hores']['P20']/$quantitat);
            $resposta['Hores']['H21'] = CongestioController::getQualificacio((float)$resposta['Hores']['H21']/$quantitat);
            $resposta['Hores']['P21'] = CongestioController::getQualificacio((float)$resposta['Hores']['P21']/$quantitat);
            $resposta['Hores']['H22'] = CongestioController::getQualificacio((float)$resposta['Hores']['H22']/$quantitat);
            $resposta['Hores']['P22'] = CongestioController::getQualificacio((float)$resposta['Hores']['P22']/$quantitat);
            $resposta['Hores']['H23'] = CongestioController::getQualificacio((float)$resposta['Hores']['H23']/$quantitat);
            $resposta['Hores']['P23'] = CongestioController::getQualificacio((float)$resposta['Hores']['P23']/$quantitat);
            $resposta['Hores']['H24'] = CongestioController::getQualificacio((float)$resposta['Hores']['H24']/$quantitat);
            $resposta['Hores']['P24'] = CongestioController::getQualificacio((float)$resposta['Hores']['P24']/$quantitat);
            $array_resultats[$keys[$index]] = $resposta;
        }
        return $array_resultats;
    }

    private static function getQualificacio($estat){
        if($estat==0){
            return "Sense dades";
        }else if($estat>0 && $estat<1.5){
            return "Molt fluid";
        }else if($estat>=1.5 && $estat<2.5){
            return "Fluid";
        }else if($estat>=2.5 && $estat<3.5){
            return "Dens";
        }else if($estat>=3.5 && $estat<4.5){
            return "Molt dens";
        }
        else if($estat>=4.5 && $estat<5.5){
            return "Congestionat";
        }
        else if($estat>=5.5){
            return "Tallat";
        }else{
            return "Sense dades";
        }
    }
}
