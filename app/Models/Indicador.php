<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicadors';
    public $incrementing = false;
    protected $keyType = 'string';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'nom','contaminant_id','bo','moderat','regular','dolent','molt_dolent','llindar'
    ];

    
    /**
     * Get the contaminant that belong to this Indicador
     */
    public function contaminant()
    {
        return $this->belongsTo('\App\Models\Contaminant');
    }

    /**
     * Get the resultats that belong to this Indicador
     */
    public function resultats()
    {
        return $this->hasMany('\App\Models\Resultat');
    }

}