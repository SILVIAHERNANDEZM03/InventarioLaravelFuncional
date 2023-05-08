<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SalidaModel;
use App\Models\StockModel;
use App\Models\viewSalidaModel;
use App\Models\ProductoModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Redirect;
use Barryvdh\DomPDF\Facade;
use PDF;

class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$productos = ProductoModel::select('*')->get();
        $salidas = viewSalidaModel::select('*')->orderBy('idsalidas', 'ASC');
        $limit=(isset($request->limit)) ? $request->limit:10;
        if(isset($request->search)){
            $salidas = $salidas
            ->where('idsalidas', 'like', '%'.$request->search.'%')
            ->orWhere('nombre', 'like', '%'.$request->search.'%')
            ->orWhere('descripcion', 'like', '%'.$request->search.'%')
            ->orWhere('precio', 'like', '%'.$request->search.'%')
            ->orWhere('cantidad', 'like', '%'.$request->search.'%');

        }
        $salidas = $salidas->paginate($limit)->appends($request->all());
        return view('salidas.index', compact('salidas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = DB::table('productos')->select('productos.idProductos','productos.nombre','stock.disponible')
            ->join('stock', 'stock.idstock', '=', 'productos.idStock')
            ->where('stock.disponible', '>', '0')
            ->get();

        return view('salidas.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->idProductos== false){
            return Redirect::back()->with('danger', 'Producto no seleccionado');

        }

        $productos = DB::table('productos')->select('productos.idProductos','productos.nombre','stock.disponible')
        ->join('stock', 'stock.idstock', '=', 'productos.idStock')
        ->where('productos.idProductos', '=', $request->idProductos)
        ->get();

        if($request->cantidad > $productos[0]->disponible){
            return Redirect::back()->with('danger', 'Productos en Stock no cubren lo requerido');

        }    

        $salida = new SalidaModel();
        $salida = $this->createUpdateSalida($request, $salida);
        return redirect()
        ->route('salidas.index')
        ->with('message', 'Se ha creado el registro correctamente.');
    }

    public function createUpdateSalida(Request $request, $salida){

        $idstock = ProductoModel::select('idStock')->where('idProductos', '=', $request->idProductos)->get();
 
        $consultStock = StockModel::select('disponible','SalidasTotales')->where('idStock',  $idstock[0]->idStock)->get();
        $existencias = $consultStock[0]->disponible;

        $salidas = $consultStock[0]->SalidasTotales;
        $totalCambio = $existencias - $request->cantidad;
        $totalsalidas = $salidas + $request->cantidad;

        DB::table('stock')->where('idStock', $idstock[0]->idStock)->update(['disponible' => $totalCambio]);
        DB::table('stock')->where('idStock', $idstock[0]->idStock)->update(['SalidasTotales' => $totalsalidas]);

        
        $salida->idProductos=$request->idProductos;
        $salida->cantidad=$request->cantidad;
        $salida->save();
        return $salida;

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $productos = ProductoModel::select('*')->get();
        $salida=SalidaModel::where('idsalidas', $id)->firstOrFail();
        return view('salidas.show', compact('salida', 'productos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productos = ProductoModel::select('*')->get();
        $salida=SalidaModel::where('idSalida', $id)->firstOrFail();
        return view('salidas.edit', compact('salida', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $salida=SalidaModel::where('idSalida', $id)->firstOrFail();
        $salida=$this->createUpdateSalida($request, $salida);
        return redirect ()
        ->route('salidas.index')
        ->with('message', 'Se ha actualizado el registro correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //$salida=SalidaModel::findOrFail($id);

        $productos = DB::table('salidas')->select('stock.idstock','salidas.cantidad','stock.salidasTotales','stock.disponible')
        ->join('productos', 'productos.idproductos', '=', 'salidas.idproductos')
        ->join('stock', 'stock.idstock', '=', 'productos.idStock')
        ->where('salidas.idsalidas', '=', $id)
        ->get();
        $salidasTotalesAntes = $productos[0]->salidasTotales;
        $disponibleAntes = $productos[0]->disponible;
        $idstock = $productos[0]->idstock;
        $salida = $productos[0]->cantidad;

        $salidasTotalesAntes = $salidasTotalesAntes - $salida;
        $disponibleAntes = $disponibleAntes + $salida;

      
            //$salida->delete();
            DB::table('stock')
            ->where('idstock', $idstock)
            ->update(['salidasTotales' => $salidasTotalesAntes,
                        'disponible'=>$disponibleAntes]);
            DB::table('salidas')->where('idsalidas', '=', $id)->delete();

            return redirect()
            ->route('salidas.index')
            ->with('message', 'Registro eliminado correctamente.');

    }
    public function exportPDF()
{
    $salidas =viewSalidaModel::all();
    //$pdf =Fadecade::loadView('salidas.exportPDF', compact('salidas'));
    $pdf = PDF::loadView('salidas.exportPDF', compact('salidas'));
    $pdf->setPaper('a4', 'landscape');

    // Descarga el PDF
    // return $pdf->download('proveedores.pdf');

    // Muestra el PDF en el navegador
    return $pdf->stream();
}  
}

 
