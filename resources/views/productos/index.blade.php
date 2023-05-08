@extends('layouts.app')
@section('content')
<div class="container">
<div class="card mt-3">
    <div class="card-header d-inline-flex">
        <h5>Entradas Productos</h5>
        <a href="{{route('productos.create')}}" class="btn btn-primary ml-auto">
            <i class="fas fa-plus"></i>
            Agregar
        </a>
        <a href="{{ route('productos.pdf') }}" class="btn btn-secondary ml-auto">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <a class="navbar-brand">Listar</a>
                    <select class="custom-select" id="limit" name="limit">
                        @foreach([10,20,50,100] as $limit)
                        <option value="{{$limit}}" @if(isset($_GET['limit']))
                            {{($_GET['limit']==$limit)?'selected': ''}}@endif>{{$limit}}</option>
                        @endforeach
                    </select>

                    <?php
                        if(isset($_GET['page'])){
                            $pag=$_GET['page'];
                        }else{
                            $pag=1;
                        }
                        if(isset($_GET['limit'])){
                            $limite=$_GET['limit'];
                        }else{
                            $limite=10;
                        }
                        $comienzo=$limite*($pag-1);
                        ?>

                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <a class="navbar-brand">Buscar</a>
                    <input class="form-control mr-sm-2" type="search" id="search" placeholder="Search"
                        aria-label="Search" value="{{ (isset($_GET['search']))?$_GET['search']:'' }}">
                </div>
            </div>
            @if($productos->total() > 10)
            {{$productos->links()}}
            @endif
        
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha entrada</th>
                        <th>Precio</th>
                        <th>Id Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $valor=1;
            if($pag!=1){
                 $valor=$comienzo+1;
            }
    ?>
                    @foreach($productos as $producto)
                    <tr>
                        <th scope="row">{{$valor++}}</th>
                        <td>{{ $producto->idproductos}}</td>
                        <td>{{ $producto->nombre}}</td>
                        <td>{{ $producto->descripcion}}</td>
                        <td>{{ $producto->expiracion}}</td>
                        <td>{{ $producto->precio}}</td>
                        <td>{{ $producto->proveedores->nombreCompleto}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{route('productos.show', $producto->idproductos)}}" class="btn btn-info"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{route('productos.edit', $producto->idproductos)}}" class="btn btn-primary"><i
                                        class="fas fa-pencil-alt"></i></a>
                                <button type="submit" class="btn btn-danger" form="delete_{{$producto->idproductos}}"
                                    onclick="return confirm('¿Estás seguro de eliminar el registro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form action="{{route('productos.destroy', $producto->idproductos)}}"
                                    id="delete_{{$producto->idproductos}}" method="post" enctype="multipart/form-data"
                                    hidden>
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>


        </div>

    </div>
    <div class="card-footer">
        @if($productos->total() > 10)
        {{$productos->links()}}
        @endif

    </div>
</div>
</div>
@endsection

@section('scripts')
<Script type="text/javascript">
$('#limit').on('change', function() {
    window.location.href = "{{ route('productos.index')}}?limit=" + $(this).val() + '&search=' + $('#search')
        .val()
})

$('#search').on('keyup', function(e) {
    if (e.keyCode == 13) {
        window.location.href = "{{ route('productos.index')}}?limit=" + $('#limit').val() + '&search=' + $(this)
            .val()
    }
})
</Script>
@endsection