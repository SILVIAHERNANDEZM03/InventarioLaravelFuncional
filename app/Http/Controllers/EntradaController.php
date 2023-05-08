<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\StockModel;
use App\Models\viewStockModel;
use App\Models\viewSalidaModel;
use Illuminate\Database\QueryException;
use App\Models\EntradaModel;
use Barryvdh\DomPDF\Facade;
use PDF;


class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = ProductoModel::select('*')->get();
        return view('entradas.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $entrada = new StockModel();
        $entrada = $this->createUpdateEntrada($request, $entrada);
        return redirect()
        ->route('entradas.index')
        ->with('message', 'Se ha creado el registro correctamente.');
    }

    public function createUpdateEntrada(Request $request, $entrada){
        $entrada->cantidad=$request->cantidad;
        $entrada->disponible=$request->disponible;
        $entrada->ultimoIngreso=$request->ultimoIngreso;
        $entrada->salidasTotales=$request->salidasTotales;

        $entrada->save();
        return $entrada;

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entrada=StockModel::where('idstock', $id)->firstOrFail();
        return view('entradas.show', compact('entrada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $entrada=StockModel::where('idstock', $id)->firstOrFail();
        return view('entradas.edit', compact('entrada'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entrada=StockModel::findOrFail($id);
        try{
            $entrada->delete();
            return redirect()
            ->route('entradas.index')
            ->with('message', 'Registro eliminado correctamente.');
        }catch(QueryException $e){
            return redirect()
            ->route('entradas.index')
            ->with('danger', 'Registro relacionado imposible de eliminar.');
        }
    }
    public function exportPDF()
    {
        $stocks = viewStockModel::all();
        $entrada = viewSalidaModel::all();
        //$pdf =Fadecade::loadView('entradas.exportPDF', compact('entradas'));
        $pdf = PDF::loadView('stocks.exportPDF', compact('stocks'));
        $pdf->setPaper('a4', 'landscape');
    
        // Descarga el PDF
        // return $pdf->download('stocks.pdf');
    
        // Muestra el PDF en el navegador
        return $pdf->stream();
    }   
}
