<?php

namespace App\Http\Controllers;
use App\Models\Mostra;
use App\Models\Contaminant;
use App\Models\Estacio;
use Illuminate\Http\Request;

class MostraController extends Controller
{
    //CRUD OPERATIONS

    //Create
    public static function createMostra(Request $request)
    {
        $contaminant_id = $request->only('contaminant_id');
        $estacio_id = $request->only('estacio_id');
        try{
            $contaminant = Contaminant::findOrFail($contaminant_id)->first();
            $estacio = Estacio::findOrFail($estacio_id)->first();
        }catch (ModelNotFoundException $e) {
            return response('Contaminant or Estacio not found', 404);
        }
        if($estacio_id['estacio_id'] == 58){ //Station 58 is added manually and relationship between its contaminants its needed
     
            $estacio = Estacio::find($estacio_id)->first();
            $contaminant = Contaminant::find($contaminant_id)->first();
            if (!$estacio->contaminants->contains($contaminant)){
                $estacio->contaminants()->attach($contaminant);
            }
            
        }

        $mostra = Mostra::create($request->all());
        return response()->json($mostra, 201);
    }

    //Read all
    public static function showAllMostres()
    {
        return response()->json(Mostra::all());
    }

    //Read one
    public static function showOneMostra($mostra_id)
    {
        return response()->json(Mostra::find($mostra_id));
    }

    public static function showAllResultatsFromMostra($mostra_id, Request $request)
    {
        try{
            $mostra = Mostra::findOrFail($mostra_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }

        $resultats = $mostra->resultats;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }      
        return response()->json($resultats, 200);
    }

    public static function showContaminantFromMostra($mostra_id){
        try{
            $mostra = Mostra::findOrFail($mostra_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }
        $contaminant = $mostra->contaminant;

        return response()->json($contaminant, 200);
    }

    public static function showEstacioFromMostra($mostra_id){
        try{
            $mostra = Mostra::findOrFail($mostra_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }
        $estacio = $mostra->estacio;

        return response()->json($estacio, 200);
    }

    public static function showResultatFromMostra($mostra_id, $resultat_id){
        try{
            $mostra = Mostra::findOrFail($mostra_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }
        $resultat = $mostra->resultats
                       ->where('id', '=', $resultat_id)
                       ->first();
        return response()->json($resultat, 200);
    }

    //Update
    public static function updateMostra($mostra_id, Request $request)
    {
        try{
             $mostra = Mostra::findOrFail($mostra_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }

        $contaminant_id = $request->only('contaminant_id');
        $estacio_id = $request->only('estacio_id');
        if($estacio_id['estacio_id'] == 58){ //Station 58 is added manually and relationship between its contaminants its needed
     
            $estacio = Estacio::find($estacio_id)->first();
            $contaminant = Contaminant::find($contaminant_id)->first();
            if (!$estacio->contaminants->contains($contaminant)){
                $estacio->contaminants()->attach($contaminant);
            }
        }

        $mostra->update($request->except(['id','estacio_id','contaminant_id']));
        return response()->json($mostra, 200);
    }

    //Delete
    public static function  deleteMostra($mostra_id)
    {
        Mostra::findOrFail($mostra_id)->delete();
        return response('Deleted Successfully', 200);
    }


}
