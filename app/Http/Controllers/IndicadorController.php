<?php

namespace App\Http\Controllers;
use App\Models\Indicador;
use App\Models\Contaminant;
use App\Models\Resultat;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    //CRUD OPERATIONS
    //Create
    public static function createIndicador(Request $request)
    {
        $contaminant_id = $request->only('contaminant_id');
        $id = $request->nom;
        $id .= '_';
        $id .= $contaminant_id['contaminant_id'];
        $request->request->add([
            'id' => $id,
            ]
        );

        try{
            $contaminant = Contaminant::findOrFail($contaminant_id);
        }catch (ModelNotFoundException $e) {
            return response('Contaminant or Estacio not found', 404);
        }
        
        $indicador = Indicador::create($request->all()); 
        return response()->json($indicador, 201);
    }

    //Read all
    public static function showAllIndicadors()
    {
        return response()->json(Indicador::all());
    }

    //Read one
    public static function showOneIndicador($indicador_id)
    {
        return response()->json(Indicador::find($indicador_id));
    }

    public static function showAllResultatsFromIndicador($indicador_id, Request $request)
    {
        try{
            $indicador = Indicador::findOrFail($indicador_id);
        }catch (ModelNotFoundException $e) {
            return response('Indicador not found', 404);
        }
        
        $resultats = $indicador->resultats;

        if(!empty($request->query())){
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $resultats = $resultats->where($condition[0],$condition[1]);
            }
        }
        return response()->json($resultats, 200);
    }

    public static function showResultatFromIndicador($indicador_id, $resultat_id)
    {
        try{
            $indicador = Indicador::findOrFail($indicador_id);
        }catch (ModelNotFoundException $e) {
            return response('Indicador not found', 404);    
        }
        $resultat = $indicador->resultats
                       ->where('id', '=', $resultat_id)
                       ->first();
        return response()->json($resultat, 200);
    }

    public static function showContaminantFromIndicador($indicador_id){
        try{
            $indicador = Indicador::findOrFail($indicador_id);
        }catch (ModelNotFoundException $e) {
            return response('Indicador not found', 404);    
        }
        $contaminant = $indicador->contaminant;

        return response()->json($contaminant, 200);
    }

    //Update
    public static function updateIndicador($indicador_id, Request $request)
    {
        try{
            $indicador = Indicador::findOrFail($indicador_id);
        }catch (ModelNotFoundException $e) {
            return response('Indicador not found', 404);    
        }
        
        $indicador->update($request->except('id','contaminant_id'));
        return response()->json($indicador, 200);
    }

    //Delete
    public static function deleteIndicador($indicador_id)
    {
        Indicador::findOrFail($indicador_id)->delete();
        return response('Deleted Successfully', 200);
    }


}
