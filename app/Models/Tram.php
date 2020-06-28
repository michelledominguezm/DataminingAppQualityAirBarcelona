<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tram extends Model
{
    protected $table = 'trams';
    public $incrementing = false;

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', "descripcio", "coordenades"
    ];

    
    /**
     * Get the estats that belong to this Tram
     */
    public function estats()
    {
        return $this->hasMany('\App\Models\Estat');
    }

    /**
     * Get the congestions that belong to this Tram
     */
    public function congestions()
    {
        return $this->hasMany('\App\Models\Congestio');
    }

}