<?php
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Main Page
$router->get('/',[ 'as' => 'map','uses' => 'Controller@index']);
$router->get('/mapa',function ()    {
  return redirect()->route('map');
});
$router->get('/grafics',function ()    {
  return redirect()->route('map');
});

$router->get('/resultats', ['uses' => 'DataController@createResults']);
$router->get('/congestions', ['uses' => 'DataController@createCongestions']);

// API routes to be used by Frontend
$router->group(['prefix' => 'api'], function () use ($router) {
  //CONTAMINANTS 
  $router->post('/contaminants', ['uses' => 'ContaminantController@createContaminant']);
  $router->get('/contaminants', ['uses' => 'ContaminantController@showAllContaminants']);
  $router->get('/contaminants/{contaminant_id}', ['uses' => 'ContaminantController@showOneContaminant']);
  $router->get('/contaminants/{contaminant_id}/estacions', ['uses' => 'ContaminantController@showAllEstacionsFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/mostres', ['uses' => 'ContaminantController@showAllMostresFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/immissions', ['uses' => 'ContaminantController@showAllImmissionsFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/indicadors', ['uses' => 'ContaminantController@showAllIndicadorsFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/resultats', ['uses' => 'ContaminantController@showAllResultatsFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/estacions/{estacio_id}', ['uses' => 'ContaminantController@showEstacioFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/mostres/{mostra_id}', ['uses' => 'ContaminantController@showMostraFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/immissions/{immissio_id}', ['uses' => 'ContaminantController@showImmissioFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/indicadors/{indicador_id}', ['uses' => 'ContaminantController@showIndicadorFromContaminant']);
  $router->get('/contaminants/{contaminant_id}/resultats/{resultat_id}', ['uses' => 'ContaminantController@showResultatFromContaminant']);
  $router->put('/contaminants/{contaminant_id}', ['uses' => 'ContaminantController@updateContaminant']);
  $router->delete('/contaminants/{contaminant_id}', ['uses' => 'ContaminantController@deleteContaminant']);
  //ESTACIONS 
  $router->post('/estacions', ['uses' => 'EstacioController@createEstacio']);
  $router->get('/estacions', ['uses' => 'EstacioController@showAllEstacions']);
  $router->get('/estacions/{estacio_id}', ['uses' => 'EstacioController@showOneEstacio']);
  $router->get('/estacions/{estacio_id}/contaminants', ['uses' => 'EstacioController@showAllContaminantsFromEstacio']);
  $router->get('/estacions/{estacio_id}/mostres', ['uses' => 'EstacioController@showAllMostresFromEstacio']);
  $router->get('/estacions/{estacio_id}/immissions', ['uses' => 'EstacioController@showAllImmissionsFromEstacio']);
  $router->get('/estacions/{estacio_id}/resultats', ['uses' => 'EstacioController@showAllResultatsFromEstacio']);
  $router->get('/estacions/{estacio_id}/contaminants/{contaminant_id}', ['uses' => 'EstacioController@showContaminantFromEstacio']);
  $router->get('/estacions/{estacio_id}/mostres/{mostra_id}', ['uses' => 'EstacioController@showMostraFromEstacio']);
  $router->get('/estacions/{estacio_id}/immissions/{immissio_id}', ['uses' => 'EstacioController@showImmissioFromEstacio']);
  $router->get('/estacions/{estacio_id}/resultats/{resultat_id}', ['uses' => 'EstacioController@showResultatFromEstacio']);
  $router->put('/estacions/{estacio_id}', ['uses' => 'EstacioController@updateEstacio']);
  $router->delete('/estacions/{estacio_id}', ['uses' => 'EstacioController@deleteEstacio']);
  //MOSTRES 
  $router->post('/mostres', ['uses' => 'MostraController@createMostra']);
  $router->get('/mostres', ['uses' => 'MostraController@showAllMostres']);
  $router->get('/mostres/{mostra_id}', ['uses' => 'MostraController@showOneMostra']);
  $router->get('/mostres/{mostra_id}/resultats/', ['uses' => 'MostraController@showAllResultatsFromMostra']);
  $router->get('/mostres/{mostra_id}/contaminant', ['uses' => 'MostraController@showContaminantFromMostra']);
  $router->get('/mostres/{mostra_id}/estacio', ['uses' => 'MostraController@showEstacioFromMostra']);
  $router->get('/mostres/{mostra_id}/resultats/{resultat_id}', ['uses' => 'MostraController@showResultatFromMostra']);
  $router->put('/mostres/{mostra_id}', ['uses' => 'MostraController@updateMostra']);
  $router->delete('/mostres/{mostra_id}', ['uses' => 'MostraController@deleteMostra']);
  //IMMISSIONS 
  $router->post('/immissions', ['uses' => 'ImmissioController@createImmissio']);
  $router->get('/immissions', ['uses' => 'ImmissioController@showAllImmissions']);
  $router->get('/immissions/{immissio_id}', ['uses' => 'ImmissioController@showOneImmissio']);
  $router->get('/immissions/{immissio_id}/resultats', ['uses' => 'ImmissioController@showAllResultatsFromImmissio']);
  $router->get('/immissions/{immissio_id}/contaminant', ['uses' => 'ImmissioController@showContaminantFromImmissio']);
  $router->get('/immissions/{immissio_id}/estacio', ['uses' => 'ImmissioController@showEstacioFromImmissio']);
  $router->get('/immissions/{immissio_id}/resultats/{resultat_id}', ['uses' => 'ImmissioController@showResultatFromImmissio']);
  $router->put('/immissions/{immissio_id}', ['uses' => 'ImmissioController@updateImmissio']);
  $router->delete('/immissions/{immissio_id}', ['uses' => 'ImmissioController@deleteImmissio']);
  //TRAMS 
  $router->post('/trams', ['uses' => 'TramController@createTram']);
  $router->get('/trams', ['uses' => 'TramController@showAllTrams']);
  $router->get('/trams/{tram_id}', ['uses' => 'TramController@showOneTram']);
  $router->get('/trams/{tram_id}/estats', ['uses' => 'TramController@showAllEstatsFromTram']);
  $router->get('/trams/{tram_id}/congestions', ['uses' => 'TramController@showAllCongestionsFromTram']);
  $router->get('/trams/{tram_id}/estats/{estat_id}', ['uses' => 'TramController@showEstatFromTram']);
  $router->get('/trams/{tram_id}/congestions/{congestio_id}', ['uses' => 'TramController@showCongestioFromTram']);
  $router->put('/trams/{tram_id}', ['uses' => 'TramController@updateTram']);
  $router->delete('/trams/{tram_id}', ['uses' => 'TramController@deleteTram']);
  //ESTATS 
  $router->post('/estats', ['uses' => 'EstatController@createEstat']);
  $router->get('/estats', ['uses' => 'EstatController@showAllEstats']);
  $router->get('/estats/{estat_id}', ['uses' => 'EstatController@showOneEstat']);
  $router->get('/estats/{estat_id}/tram', ['uses' => 'EstatController@showTramFromEstat']);
  $router->get('/estats/{estat_id}/congestio', ['uses' => 'EstatController@showCongestioFromEstat']);
  $router->put('/estats/{estat_id}', ['uses' => 'EstatController@updateEstat']);
  $router->delete('/estats/{estat_id}', ['uses' => 'EstatController@deleteEstat']);
  //INDICADORS
  $router->post('/indicadors', ['uses' => 'IndicadorController@createIndicador']);
  $router->get('/indicadors', ['uses' => 'IndicadorController@showAllIndicadors']);
  $router->get('/indicadors/{indicador_id}', ['uses' => 'IndicadorController@showOneIndicador']);
  $router->get('/indicadors/{indicador_id}/resultats', ['uses' => 'IndicadorController@showAllResultatsFromIndicador']);
  $router->get('/indicadors/{indicador_id}/resultats/{resultat_id}', ['uses' => 'IndicadorController@showResultatFromIndicador']);
  $router->get('/indicadors/{indicador_id}/contaminant', ['uses' => 'IndicadorController@showContaminantFromIndicador']);
  $router->put('/indicadors/{indicador_id}', ['uses' => 'IndicadorController@updateIndicador']);
  $router->delete('/indicadors/{indicador_id}', ['uses' => 'IndicadorController@deleteIndicador']);
  //RESULTATS CONTAMINACIO
  $router->post('/resultats', ['uses' => 'ResultatController@createResultat']);
  $router->get('/resultats', ['uses' => 'ResultatController@showAllResultats']);

  $router->get('/resultats/periode', ['uses' => 'ResultatController@showResultatsFromPeriode']);
  $router->get('/resultats/dies', ['uses' => 'ResultatController@showResultatsFromDies']); 

  $router->get('/resultats/{resultat_id}', ['uses' => 'ResultatController@showOneResultat']);
  $router->get('/resultats/{resultat_id}/contaminant', ['uses' => 'ResultatController@showContaminantFromResultat']);
  $router->get('/resultats/{resultat_id}/estacio', ['uses' => 'ResultatController@showEstacioFromResultat']);
  $router->get('/resultats/{resultat_id}/indicador', ['uses' => 'ResultatController@showIndicadorFromResultat']);
  $router->get('/resultats/{resultat_id}/mostra', ['uses' => 'ResultatController@showMostraFromResultat']);
  $router->get('/resultats/{resultat_id}/immissio', ['uses' => 'ResultatController@showImmissioFromResultat']);
  $router->put('/resultats/{resultat_id}', ['uses' => 'ResultatController@updateResultat']);
  $router->delete('/resultats/{resultat_id}', ['uses' => 'ResultatController@deleteResultat']);
  //RESULTATS TRANSIT
  $router->post('/congestions', ['uses' => 'CongestioController@createCongestio']);
  $router->get('/congestions', ['uses' => 'CongestioController@showAllCongestions']);

  $router->get('/congestions/periode', ['uses' => 'CongestioController@showCongestionsFromPeriode']);
  $router->get('/congestions/dies', ['uses' => 'CongestioController@showCongestionsFromDies']); 

  $router->get('/congestions/{congestio_id}', ['uses' => 'CongestioController@showOneCongestio']);
  $router->get('/congestions/{congestio_id}/tram', ['uses' => 'CongestioController@showTramFromCongestio']);
  $router->get('/congestions/{congestio_id}/estats', ['uses' => 'CongestioController@showAllEstatsFromCongestio']);
  $router->get('/congestions/{congestio_id}/estats/{estat_id}', ['uses' => 'CongestioController@showEstatFromCongestio']);
  $router->put('/congestions/{congestio_id}', ['uses' => 'CongestioController@updateCongestio']);
  $router->delete('/congestions/{congestio_id}', ['uses' => 'CongestioController@deleteCongestio']);
});

// Routes to get data and fill the database
$router->group(['prefix' => 'opendata'], function () use ($router) {
  //MOSTRES
  $router->get('mostres_qualitat', 'ApiController@getAirQualityDataCSV');
  //IMMISSIONS
  $router->get('immissions_qualitat', 'ApiController@getAirPollutionData');
  //CONTAMINANTS
  $router->get('contaminants', 'ApiController@getAirPollutantsData');
  //ESTACIONS
  $router->get('estacions_mesura', 'ApiController@getAirStationsData');
  //ESTATS
  $router->get('estat_transit', 'ApiController@getTrafficData');
  //TRAMS
  $router->get('trams_transit', 'ApiController@getSectionsData');
});

