@csrf
<div class="container">
<div class= "row">

    <div class="col-12">
        <div class="form-group">
            <label for="">Ingresos</label>
            <input type="text" class="form-control" name="cantidad" value="{{(isset($entrada))?$entrada->cantidad:old('cantidad')}}" required>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="">Stock</label>
            <input type="text" class="form-control" name="disponible" value="{{(isset($entrada))?$entrada->disponible:old('disponible')}}" required>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="">Último Ingreso</label>
            <input type="text" class="form-control" name="ultimoIngreso" value="{{(isset($entrada))?$entrada->ultimoIngreso:old('ultimoIngreso')}}" required>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="">Salidas Totales</label>
            <input type="text" class="form-control" name="salidasTotales" value="{{(isset($entrada))?$entrada->salidasTotales:old('salidasTotales')}}" required>
        </div>
    </div>

