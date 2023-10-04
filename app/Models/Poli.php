<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'poli';
    protected $primaryKey = 'id_poli';
    protected $fillable = ['id_poli','nama', 'jenis_poli'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_poli'];
    }

    // public function dokter()
    // {
    //     return $this->hasMany(Dokter::class);
    // }
}


