<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $table = 'hasil';
    protected $fillable = [
        'penduduk_id',
        'cluster',
        'jarak',
        'iterasi'
    ];

} 