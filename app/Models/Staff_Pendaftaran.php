<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff_Pendaftaran extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'karyawan_non_nakes';
    protected $primaryKey = 'id_karyawan';
    protected $fillable = ['id_karyawan','nama','jenis'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_karyawan'];
    }

}