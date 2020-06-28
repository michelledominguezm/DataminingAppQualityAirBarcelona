<?php

namespace App\Http\Controllers;
use App\Models\Tram;
use App\Models\Congestio;
use Illuminate\Http\Request;

class TramController extends Controller
{
    //CRUD OPERATIONS

    //Create
    public static function createTram(Request $request)
    {
        $tram = Tram::create($request->all());
        return response()->json($tram, 201);
    }

    //Read all
    public static function showAllTrams()
    {
        return response()->json(Tram::all());
    }

    //Read one
    public static function showOneTram($tram_id)
    {
        return response()->json(Tram::find($tram_id));
    }

    public static function showAllEstatsFromTram($tram_id, Request $request)
    {
        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);
        }
        
        $estats = $tram->estats;

        if(!empty($request->query())){
            
            $query = $request->query();
            foreach ($query as $name => $value){
                set_time_limit(14400);
                $condition = [$name, $value];

                $estats = $estats->where($condition[0],$condition[1]);
            }       
        }

        return response()->json($estats, 200);
    }

    public static function showAllCongestionsFromTram($tram_id, Request $request)
    {
        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);
        }
        
        $congestions = $tram->congestions;

        if(!empty($request->query())){
            
            $query = $request->query();
            foreach ($query as $name => $value){
                $condition = [$name, $value];

                $congestions = $congestions->where($condition[0],$condition[1]);
            }       
        }

        return response()->json($congestions, 200);
    }

    public static function showEstatFromTram($tram_id, $estat_id)
    {
        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);    
        }
        $estat = $tram->estats
                       ->where('id', '=', $estat_id)
                       ->first();
        return response()->json($estat, 200);
    }

    public static function showCongestioFromTram($tram_id, $congestio_id)
    {
        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);    
        }
        $congestio = $tram->congestions
                       ->where('id', '=', $congestio_id)
                       ->first();
        return response()->json($congestio, 200);
    }

    //Update
    public static function updateTram($tram_id, Request $request)
    {
        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);    
        }
        
        $tram->update($request->all());
        return response()->json($tram, 200);
    }

    //Delete
    public static function deleteTram($tram_id)
    {
        Tram::findOrFail($tram_id)->delete();
        return response('Deleted Successfully', 200);
    }


}
