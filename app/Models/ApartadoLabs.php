<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartadoLabs extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'start_date',
        'end_date',
        'lab_id',
        'user_id'
    ];

    protected $table = 'apartado_labs';
}
