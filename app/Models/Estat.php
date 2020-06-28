<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Estat extends Model
{
    protected $table = 'estats';
    public $incrementing = false;
    protected $keyType = 'string';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'tram_id','congestio_id','data','any','mes','dia','hora','minuts', 'estat_actual','estat_previst'
    ];

    
    /**
     * Get the tram that belong to this Estat
     */
    public function tram()
    {
        return $this->belongsTo('\App\Models\Tram');
    }

    /**
     * Get the congestio that belong to this Estat
     */
    public function congestio()
    {
        return $this->belongsTo('\App\Models\Congestio');
    }

}