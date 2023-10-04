<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';
    protected $fillable = ['id_dokter','nama','no_telp','id_poli'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_dokter'];
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}