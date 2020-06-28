<?php

namespace App\Http\Controllers;
use App\Models\Immissio;
use App\Models\Estacio;
use App\Models\Contaminant;

use Illuminate\Http\Request;

class ImmissioController extends Controller
{
    //CRUD OPERATIONS

    //Create
    public static function createImmissio(Request $request)
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

        $mostra = Immissio::create($request->all());
        return response()->json($mostra, 201);
    }

    //Read all
    public static function showAllImmissions()
    {
        return response()->json(Immissio::all());
    }

    //Read one
    public static function showOneImmissio($immissio_id)
    {
        return response()->json(Immissio::find($immissio_id));
    }

    public static function showAllResultatsFromImmissio($immissio_id, Request $request)
    {
        try{
            $immissio = Immissio::findOrFail($immissio_id);
        }catch (ModelNotFoundException $e) {
            return response('Immissio not found', 404);    
        }

        $resultats = $immissio->resultats;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }      
        return response()->json($resultats, 200);
    }

    public static function showContaminantFromImmissio($immissio_id){
        try{
            $immissio = Immissio::findOrFail($immissio_id);
        }catch (ModelNotFoundException $e) {
            return response('Immissio not found', 404);    
        }
        $contaminant = $immissio->contaminant;

        return response()->json($contaminant, 200);
    }

    public static function showEstacioFromImmissio($immissio_id){
        try{
            $immissio = Immissio::findOrFail($immissio_id);
        }catch (ModelNotFoundException $e) {
            return response('Immissio not found', 404);    
        }
        $estacio = $immissio->estacio;

        return response()->json($estacio, 200);
    }

    public static function showResultatFromImmissio($immissio_id, $resultat_id){
        try{
            $immissio = Immissio::findOrFail($immissio_id);
        }catch (ModelNotFoundException $e) {
            return response('Immissio not found', 404);    
        }
        $resultat = $immissio->resultats
                       ->where('id', '=', $resultat_id)
                       ->first();
        return response()->json($resultat, 200);
    }

    //Update
    public static function updateImmissio($immissio_id, Request $request)
    {
        try{
            $immissio = Immissio::findOrFail($immissio_id);
        }catch (ModelNotFoundException $e) {
            return response('Immissio not found', 404);    
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
        
        
        $immissio->update($request->except(['id','estacio_id','contaminant_id']));
        return response()->json($immissio, 200);
    }

    //Delete
    public static function  deleteImmissio($immissio_id)
    {
        Immissio::findOrFail($immissio_id)->delete();
        return response('Deleted Successfully', 200);
    }


}
