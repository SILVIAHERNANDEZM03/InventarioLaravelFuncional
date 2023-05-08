@csrf
<div class="container">
<div class= "row">
    <div class="col-12">
        <div class="form-group">
            <label for="">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{(isset($producto))?$producto->nombre:old('nombre')}}" required>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="">Descripción</label>
            <input type="text" class="form-control" name="descripcion" value="{{(isset($producto))?$producto->descripcion:old('descripcion')}}" required>
        </div>
    </div>
    
    <div class="col-12">
        <div class="form-group">
            <label for="">Fecha de Expiración</label>
            <input type="date" class="form-control" name="expiracion" value="{{(isset($producto))?$producto->expiracion:old('expiracion')}}" required>
        </div>
    </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="">Precio</label>
            <input type="text" class="form-control" name="precio" value="{{(isset($producto))?$producto->precio:old('precio')}}" required>
        
        </div>
    </div>

<div class="col-12">
        <div class="form-group">
            <label for="">Proveedor</label> 
            <select class="form-control" name="idproveedores">
    	        <option value="0">Selecciona una opción</option>
    	        @foreach($proveedores as $proveedor)
    	        	<option value="{{$proveedor->idproveedores}}" @isset($producto)
                            {{  ($producto->idproveedores == $proveedor->idproveedores)?'selected':''  }}
                        @endisset>{{ $proveedor->idproveedores }}-{{ $proveedor->nombreCompleto }}</option>
    	        @endforeach
            </select>
        </div>
</div>
