<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Congestio extends Model
{
    protected $table = 'congestions';
    public $incrementing = false;
    protected $keyType = 'string';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id', 'tram_id','data','any','mes','dia', 'H01','P01','H02','P02','H03','P03','H04','P04','H05','P05','H06','P06','H07','P07','H08','P08','H09','P09','H10','P10','H11','P11','H12','P12','H13','P13','H14','P14','H15','P15','H16','P16','H17','P17','H18','P18','H19','P19','H20','P20','H21','P21','H22','P22','H23','P23','H24','P24','actual','previst','complet'
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
    public function estats()
    {
        return $this->hasMany('\App\Models\Estat');
    }

}