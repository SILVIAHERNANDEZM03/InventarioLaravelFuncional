<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    use HasFactory;
    protected $table = 'stock';
    protected $primaryKey = 'idstock';
    protected $fillable = [
        'cantidad',
        'disponible',
        'ultimoIngreso',
];
public function producto()
{
    return $this->belongsTo('App\Models\ProductoModel', 'idProducto');
}
}