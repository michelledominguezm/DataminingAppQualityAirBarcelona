<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Contaminant extends Model
{
    protected $table = 'contaminants';
    public $incrementing = false;

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'nom', 'simbol', 'unitats', "descripcio"
    ];
    /**
     * Get the estacions that belong to this Contaminant
     */
    public function estacions()
    {
        return $this->belongsToMany('\App\Models\Estacio','contaminant_estacio');
    }
    
    /**
     * Get the mostres that belong to this Contaminant
     */
    public function mostres()
    {
        return $this->hasMany('\App\Models\Mostra');
    }
    
    /**
     * Get the immissions that belong to this Contaminant
     */
    public function immissions()
    {
        return $this->hasMany('\App\Models\Immissio');
    }
    
    /**
     * Get the indicators that belong to this Contaminant
     */
    public function indicadors()
    {
        return $this->hasMany('\App\Models\Indicador');
    }

    /**
     * Get the resultats that belong to this Contaminant
     */
    public function resultats()
    {
        return $this->hasMany('\App\Models\Resultat');
    }

}