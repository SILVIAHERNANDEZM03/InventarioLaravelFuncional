<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viewSalidaModel extends Model
{
    use HasFactory;
    protected $table = 'vista_salidas';
    protected $primaryKey = 'idsalidas';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',
];

}