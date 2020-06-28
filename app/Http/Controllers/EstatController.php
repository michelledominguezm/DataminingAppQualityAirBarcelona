<?php

namespace App\Http\Controllers;
use App\Models\Estat;
use App\Models\Tram;
use App\Models\Congestio;
use Illuminate\Http\Request;



class EstatController extends Controller
{
    //CRUD OPERATIONS
    
    //Create
    public static function createEstat(Request $request)
    {
        $tram_id = $request->only('tram_id');

        try{
            $tram = Tram::findOrFail($tram_id);
        }catch (ModelNotFoundException $e) {
            return response('Tram not found', 404);
        }
        $estat = Estat::create($request->all());

        return response()->json($estat, 201);
    }

    //Read all
    public static function  showAllEstats(Request $request)
    {
        $dia = $request->exists('dia')?$request->only('dia'):(int) date('d');
        $mes = $request->exists('mes')?$request->only('mes'):(int) date('m');
        $any = $request->exists('any')?$request->only('any'):(int) date('Y');
        set_time_limit(300);
        $estats = Estat::where('dia','=',$dia)->where('mes','=',$mes)->where('any','=',$any)->get(); 

        if(!empty($request->query())){
            $query = $request->except(['dia','mes','any']);
                foreach ($query as $name => $value){
                    $condition = [$name, $value];
    
                    $estats = $estats->where($condition[0],$condition[1]);
                }
        }
        return response()->json($estats);
    }

    //Read one
    public static function  showOneEstat($estat_id)
    {
        return response()->json(Estat::find($estat_id));
    }
    
    public static function  showTramFromEstat($estat_id){
        try{
            $estat = Estat::findOrFail($estat_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }
        $tram = $estat->tram;

        return response()->json($tram, 200);
    }

    public static function  showCongestioFromEstat($estat_id){
        try{
            $estat = Estat::findOrFail($estat_id);
        }catch (ModelNotFoundException $e) {
            return response('Mostra not found', 404);    
        }
        $congestio = $estat->congestio;

        return response()->json($congestio, 200);
    }

    //Update
    public static function  updateEstat($estat_id, Request $request)
    {
        set_time_limit(300);
        try{
            $estat = Estat::findOrFail($estat_id);
        }catch (ModelNotFoundException $e) {
            return response('Estat not found', 404);    
        }
        
        $estat->update($request->except(['id','tram_id']));
        return response()->json($estat, 200);
    }

    //Delete
    public static function  deleteEstat($estat_id)
    {
        Estat::findOrFail($estat_id)->delete();
        return response('Deleted Successfully', 200);
    }


}
