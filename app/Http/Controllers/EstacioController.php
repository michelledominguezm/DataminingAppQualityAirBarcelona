<?php

namespace App\Http\Controllers;
use App\Models\Estacio;
use App\Models\Contaminant;
use App\Models\Mostra;
use App\Models\Immissio;
use App\Models\Resultat;
use Illuminate\Http\Request;

class EstacioController extends Controller
{
    // CRUD OPERATIONS
    //Create
    public static function createEstacio(Request $request)
    {
        $contaminant_id = $request->only('contaminant_id');
        $estacio_id = $request->only('id');

        $estacio = Estacio::find($estacio_id);
        $data = '';
        if($estacio->isEmpty())
        {
            $data = $estacio = Estacio::create($request->except('contaminant_id'));
        }

        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant not found', 404);
        }
        $data =  EstacioController::linkEstacioToContaminant($estacio_id, $contaminant_id);
        return response()->json($data, 201);
    }

    //Read all
    public static function showAllEstacions()
    {
        return response()->json(Estacio::all());
    }

    //Read one
    public static function showOneEstacio($estacio_id)
    {
        return response()->json(Estacio::find($estacio_id));
    }

    public static function showAllContaminantsFromEstacio($estacio_id)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);
        }
        $contaminants = $estacio->contaminants;
        return response()->json($contaminants, 200);
    }

    public static function showAllMostresFromEstacio($estacio_id, Request $request)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);
        }
        
        $mostres = $estacio->mostres;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $mostres = $mostres->where($condition[0],$condition[1]);
            }
        }

        return response()->json($mostres, 200);
    }

    public static function showAllImmissionsFromEstacio($estacio_id, Request $request)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);
        }
        
        $immissions = $estacio->immissions;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $immissions = $immissions->where($condition[0],$condition[1]);
            }
        }
        return response()->json($immissions, 200);
    }

    public static function showAllResultatsFromEstacio($estacio_id, Request $request)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);
        }
        
        $resultats = $estacio->resultats;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }
        return response()->json($resultats, 200);
    }

    public static function showContaminantFromEstacio($estacio_id, $contaminant_id)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);    
        }
        $contaminant = $estacio->contaminants
                       ->where('id', '=', $contaminant_id)
                       ->first();
        return response()->json($contaminant, 200);
    }

    public static function showMostraFromEstacio($estacio_id, $mostra_id)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);    
        }
        $mostra = $estacio->mostres
                       ->where('id', '=', $mostra_id)
                       ->first();
        return response()->json($mostra, 200);
    }

    public static function showImmissioFromEstacio($estacio_id, $immissio_id)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);    
        }
        $immissio = $estacio->immissions
                       ->where('id', '=', $immissio_id)
                       ->first();
        return response()->json($immissio, 200);
    }

    public static function showResultatFromEstacio($estacio_id, $resultat_id)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);    
        }
        $resultat = $estacio->resultats
                       ->where('id', '=', $resultat_id)
                       ->first();
        return response()->json($resultat, 200);
    }

    //Update
    public static function updateEstacio($estacio_id, Request $request)
    {
        try{
            $estacio = Estacio::findOrFail($estacio_id);
        }catch (ModelNotFoundException $e) {
            return response('Estacio not found', 404);    
        }

        $estacio->update($request->except(['id','contaminant_id']));
        return response()->json($estacio, 200);
    }

    //Update
    public static function deleteEstacio($estacio_id)
    {
        Estacio::findOrFail($estacio_id)->delete();
        return response('Deleted Successfully', 200);
    }
    
    private static function linkEstacioToContaminant($estacio_id, $contaminant_id) //in future should have $estacio_id, $contaminant_id
    {    
        $estacio = Estacio::find($estacio_id)->first();
        $contaminant = Contaminant::find($contaminant_id)->first();

        $estacio->contaminants()->attach($contaminant);

        $data['estacio'] = $estacio;
        $data['contaminant'] = $contaminant;
        return $data;
    }



}
