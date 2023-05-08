<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\StockModel;
use App\Models\ProductoModel;
use App\Models\viewStockModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class addStockController extends Controller
{
    public function index(Request $request)
    {
        $entradas = viewStockModel::select('*')->orderBy('idstock', 'ASC');
        $limit=(isset($request->limit)) ? $request->limit:10;

        
        if(isset($request->search)){
            $entradas = $entradas
            ->where('idstock', 'like', '%'.$request->search.'%')
            ->orWhere('descripcion', 'like', '%'.$request->search.'%')
            ->orWhere('existencias', 'like', '%'.$request->search.'%')
            ->orWhere('stock', 'like', '%'.$request->search.'%')
            ->orWhere('ultimoIngreso', 'like', '%'.$request->search.'%');

        }
        $entradas = $entradas->paginate($limit)->appends($request->all());
        return view('entradas.index', compact('entradas'));
    }


    public function createUpdateEntrada(Request $request, $entrada){
        $idstock = $request->idstock;
        $cantidadNew = $request->cantidad;

        $queryUpdateStock = StockModel::select('cantidad','disponible')->where('idStock',  $idstock)->get();
        $disponible = $queryUpdateStock[0]->disponible;
        $cantidad = $queryUpdateStock[0]->cantidad;


        $entrada->cantidad = $cantidad + $cantidadNew;
       $entrada->disponible= $disponible + $cantidadNew;
        $entrada->ultimoIngreso=$request->cantidad;
       $entrada->save();
        return $entrada;

    }

    public function edit($id)
    {
        $entrada=StockModel::where('idstock', $id)->firstOrFail();
        return view('stocks.edit', compact('entrada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entrada=StockModel::where('idstock', $id)->firstOrFail();
        $entrada=$this->createUpdateEntrada($request, $entrada);
        return redirect ()
        ->route('entradas.index')
        ->with('message', 'Se ha actualizado el registro correctamente.');
    }

}


