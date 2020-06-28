<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    protected $table = 'resultats';
    public $incrementing = false;
    protected $keyType = 'string';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'estacio_id','contaminant_id','indicador_id','mostra_id','immissio_id','any','mes','dia','H01','R01','H02','R02','H03','R03','H04','R04','H05','R05','H06','R06','H07','R07','H08','R08','H09','R09','H10','R10','H11','R11','H12','R12','H13','R13','H14','R14','H15','R15','H16','R16','H17','R17','H18','R18','H19','R19','H20','R20','H21','R21','H22','R22','H23','R23','H24','R24','maxim','minim','mitjana','qualificacio','complet','valid'
    ];

    /**
     * Get the contaminant that belong to this Resultat
     */
    public function contaminant()
    {
        return $this->belongsTo('\App\Models\Contaminant');
    }
    
    /**
     * Get the estacio that belong to this Resultat
     */
    public function estacio()
    {
        return $this->belongsTo('\App\Models\Estacio');
    }

    /**
     * Get the indicador that belong to this Resultat
     */
    public function indicador()
    {
        return $this->belongsTo('\App\Models\Indicador');
    }

    /**
     * Get the mostra that belong to this Resultat
     */
    public function mostra()
    {
        return $this->belongsTo('\App\Models\Mostra');
    }

    /**
     * Get the immissio that belong to this Resultat
     */
    public function immissio()
    {
        return $this->belongsTo('\App\Models\Immissio');
    }

}