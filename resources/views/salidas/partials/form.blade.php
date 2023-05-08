@csrf

<div class="container">
<div class= "row">
<div class="col-12">
        <div class="form-group">
            <label for="">Producto</label> 
            <select class="form-control" name="idProductos">
    	        <option value="0">Selecciona una opción</option>
    	        @foreach($productos as $producto)
    	        	<option value="{{$producto->idProductos}}" @isset($salida)
                            {{  ($salida->idProductos  == $producto->idProductos )?'selected':''  }}
                        @endisset>{{ $producto->nombre }} (existencias: {{ $producto->disponible }})</option>
    	        @endforeach
            </select>
        </div></div>
    <div class="col-12">
        <div class="form-group">
            <label for="">Cantidad</label>
            <input type="number" class="form-control" name="cantidad" value="{{(isset($salida))?$salida->cantidad:old('cantidad')}}" required>
        </div>
    