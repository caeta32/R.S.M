<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'contrasenia',
        'nombre',
        'apellido',
        'nivelAcceso'
    ];

    // Convenciones de primary key
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
}
