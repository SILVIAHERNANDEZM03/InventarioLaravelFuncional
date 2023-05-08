<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaModel extends Model
{
    use HasFactory;
    protected $table = 'salidas';
    protected $primaryKey = 'idsalidas';
    protected $foreignKey = 'idProducto';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',

    ];


}
