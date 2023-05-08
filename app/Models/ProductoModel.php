<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'idProductos';
    protected $foreignKey = 'idProveedor';
    protected $table = 'productos';
    protected $fillable = [
    'nombre',
    'descripcion',
    'precio',
    'expiracion',
    'idStock',
    'idProveedor',
    ];
    public function proveedores(){
        return $this->belongsTo('App\Models\ProveedorModel', 'idProveedor', 'idProveedor');
    }
}
