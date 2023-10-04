<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pasien';
    protected $primaryKey = 'no_rekmed';
    protected $fillable = ['no_rekmed','nama','tgl_lahir', 'jenis_kelamin','no_telp','alamat', 'pekerjaan'];

    public function getPrimaryKey()
    {
        return $this->attributes['no_rekmed'];
    }

}