@csrf

<div class="container">
<div class= "row">
    <div class="col-12">
 
        <div class="form-group">
            <label for="">Cantidad</label>
            <input type="number" class="form-control" name="cantidad" value="" required>
        </div>
        <input type="number" class="form-control" name="idstock" value="{{$entrada->idstock}}" readonly hidden>
    </div>

