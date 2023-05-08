<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viewStockModel extends Model
{
    use HasFactory;
    protected $table = 'vista_stock';
    protected $primaryKey = 'idstock';
    protected $fillable = [
        'nombre',
        'descripcion',
        'existencias',
        'stock',
        'ultimoIngreso',
];

}