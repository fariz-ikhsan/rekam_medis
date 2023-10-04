<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suster extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'suster';
    protected $primaryKey = 'id_suster';
    protected $fillable = ['id_suster','nama'];

    public function getPrimaryKey()
    {
        return $this->attributes['id_suster'];
    }
}