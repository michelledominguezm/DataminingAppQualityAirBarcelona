<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Immissio extends Model
{
    protected $table = 'immissions';
    public $incrementing = false;
    protected $keyType = 'string';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'estacio_id','contaminant_id','contaminant_id','data','any','mes','dia','H01','V01','H02','V02','H03','V03','H04','V04','H05','V05','H06','V06','H07','V07','H08','V08','H09','V09','H10','V10','H11','V11','H12','V12','H13','V13','H14','V14','H15','V15','H16','V16','H17','V17','H18','V18','H19','V19','H20','V20','H21','V21','H22','V22','H23','V23','H24','V24'
    ];

    /**
     * Get the contaminant that belong to this Immissio
     */
    public function contaminant()
    {
        return $this->belongsTo('\App\Models\Contaminant');
    }
    
    /**
     * Get the estacio that belong to this Immissio
     */
    public function estacio()
    {
        return $this->belongsTo('\App\Models\Estacio');
    }

        /**
     * Get the resultat that belong to this Immissio
     */
    public function resultats()
    {
        return $this->hasMany('\App\Models\Resultat');
    }

}