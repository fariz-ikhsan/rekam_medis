<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'jadwal_dokter';
    protected $primaryKey = 'id_jdwdokter';
    protected $fillable = ['hari','buka_praktek','akhir_praktek','id_dokter'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_dokter'];
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }
}