<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Estacio extends Model
{
    protected $table = 'estacions';
    public $incrementing = false;

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'nom_estacio', 'codi_estacio', 'codi_eoi', "latitud", "longitud", "ubicacio", "codi_districte", "nom_districte", "codi_barri","nom_barri", "tipus_estacio_1", "tipus_estacio_2"
    ];
    /**
     * Get the contaminants that belong to this Estacio
     */
    public function contaminants()
    {
        return $this->belongsToMany('\App\Models\Contaminant','contaminant_estacio');
    }
    
    /**
     * Get the mostres that belong to this Estacio
     */
    public function mostres()
    {
        return $this->hasMany('\App\Models\Mostra');
    }
    
    /**
     * Get the immissions that belong to this Estacio
     */
    public function immissions()
    {
        return $this->hasMany('\App\Models\Immissio');
    }

    /**
     * Get the resultats that belong to this Estacio
     */
    public function resultats()
    {
        return $this->hasMany('\App\Models\Resultat');
    }

}