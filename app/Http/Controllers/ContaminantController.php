<?php

namespace App\Http\Controllers;
use App\Models\Contaminant;
use App\Models\Estacio;
use App\Models\Mostra;
use App\Models\Immissio;
use App\Models\Indicador;
use App\Models\Resultat;
use Illuminate\Http\Request;

class ContaminantController extends Controller
{
    //Create
    public static function createContaminant(Request $request)
    {
        $contaminant = Contaminant::create($request->all());
        return response()->json($contaminant, 201);
    }

    //Read all
    public static function showAllContaminants()
    {
        return response()->json(Contaminant::all());
    }

    //Read one
    public static function showOneContaminant($contaminant_id)
    {
        return response()->json(Contaminant::find($contaminant_id));
    }

    public static function showAllEstacionsFromContaminant($contaminant_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);
        }
        $estacions = $contaminant->estacions;
        return response()->json($estacions, 200);
    }

    public static function showAllMostresFromContaminant($contaminant_id, Request $request)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);
        }

        $mostres = $contaminant->mostres;

        if(!empty($request->query())){
            
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $mostres = $mostres->where($condition[0],$condition[1]);
            }       
        }
        
        return response()->json($mostres, 200);
    }

    public static function showAllImmissionsFromContaminant($contaminant_id,Request $request)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);
        }

        $immissions = $contaminant->immissions;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $immissions = $immissions->where($condition[0],$condition[1]);
            }
        }
        
        return response()->json($immissions, 200);
    }

    public static function showAllIndicadorsFromContaminant($contaminant_id, Request $request)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }

        $indicadors = $contaminant->indicadors;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $indicadors = $indicadors->where($condition[0],$condition[1]);
            }
        }
        
        
        return response()->json($indicadors, 200);
    }

    public static function showAllResultatsFromContaminant($contaminant_id, Request $request)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }

        $resultats = $contaminant->resultats;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }      
        return response()->json(ContaminantController::deleteRepeatedResultats($resultats), 200);
    }

    public static function showEstacioFromContaminant($contaminant_id, $estacio_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $estacio = $contaminant->estacions
                       ->where('id', '=', $estacio_id)
                       ->first();
        return response()->json($estacio, 200);
    }

    public static function showMostraFromContaminant($contaminant_id, $mostra_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $mostra = $contaminant->mostres
                       ->where('id', '=', $mostra_id)
                       ->first();
        return response()->json($mostra, 200);
    }

    public static function showImmissioFromContaminant($contaminant_id, $immissio_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $immissio = $contaminant->immissions
                       ->where('id', '=', $immissio_id)
                       ->first();
        return response()->json($immissio, 200);
    }

    public static function showIndicadorFromContaminant($contaminant_id, $indicador_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $indicador = $contaminant->indicadors
                       ->where('id', '=', $indicador_id)
                       ->first();
        return response()->json($indicador, 200);
    }

    public static function showResultatFromContaminant($contaminant_id, $resultat_id)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $resultat = $contaminant->resultats
                       ->where('id', '=', $resultat_id)
                       ->first();
        return response()->json($resultat, 200);
    }

    //Update
    public static function updateContaminant($contaminant_id, Request $request)
    {
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        $contaminant->update($request->except('id'));
        return response()->json($contaminant, 200);
    }

    //Delete
    public static function deleteContaminant($contaminant_id)
    {
        try{
            Contaminant::findOrFail($contaminant_id)->delete();
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);    
        }
        
        return response('Deleted Successfully', 200);
    }

    private static function deleteRepeatedResultats($resultats){
        $array_resultats = array();
        $array_identificadors = array();

        foreach ($resultats as $res){
            $resultat = $res->toArray();
            $estacio=$resultat['estacio_id'];
            $contaminant=$resultat['contaminant_id'];
            $any=$resultat['any'];
            $mes=$resultat['mes'];
            $dia=$resultat['dia'];
            $date=date_create($any."-".$mes."-".$dia);
            $formatted_date = $date->format('Y-n-j');  
            $indicador=$resultat['indicador_id'];
            $identificador = $estacio."-".$contaminant."-".$formatted_date."-".$indicador;
            $index = array_search($identificador, $array_identificadors);
            
            if(($index) === false){
                array_push($array_identificadors, $identificador);
                array_push($array_resultats, $resultat);
            }
        }
        return $array_resultats;

    }

}
