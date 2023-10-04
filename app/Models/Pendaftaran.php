<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    protected $fillable = ['tgl_daftar', 'no_rekmed', 'id_dokter','status'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_pendaftaran'];
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rekmed');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }
}