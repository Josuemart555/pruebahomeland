@extends('layouts.app')

@section('title_page',__('Productos'))

@section('content')

<div id="vmProducto">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Productos')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-outline-success"
                               @click="newProducto()">
                                <i class="fa fa-plus"></i>
                                <span class="d-none d-sm-inline">{{__('Nuevo')}}</span>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="modal fade" id="modal-producto" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        {{__('Crear Nuevo Producto')}}
                    </h5>
                    <button type="button" class="close" aria-label="Close" @click="cerrarModal()" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            {!! Form::label('codigo', 'Codigo:') !!}
                            {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('nombre', 'Nombre:') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('cantidad', 'Cantidad:') !!}
                            {!! Form::number('cantidad', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('precio', 'Precio:') !!}
                            {!! Form::text('precio', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_ingresa', 'Fecha Ingresa:') !!}
                            <input type="date" class="form-control" name="fecha_ingresa" >
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fehca_vencimiento', 'Fecha Vencimiento:') !!}
                            <input type="date" class="form-control" name="fehca_vencimiento" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary " @click="cerrarModal()">
                        {{__('Cancelar')}}
                    </button>
                    <button type="button" class="btn btn-outline-success " >
                        {{__('Guardar')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-body">

                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script>
        const app = new Vue({
            el: '#vmProducto',
            created() {
                this.getData();
            },
            data: {
                user : [],
            },
            methods: {
                async getData(){
                    this.user= [];
                    let url = "{{route("api.users.show",auth()->user()->id)}}";

                    try {
                        let res = await axios.get(url);

                        this.user = res.data.data;

                        logI(res);

                    }catch (e) {
                        if(e.response.data){
                            logI(e.response.data);
                        }else{
                            logI(e);
                        }

                    }
                },
                newProducto(){
                    $("#modal-producto").modal('show');
                },
                cerrarModal() {
                    $("#modal-producto").modal('hide');
                },
                editShortcut(){
                    $("#modalEditShortCuts").modal('show');
                },
                async addShortcut(option){
                    let url = "{{route("api.users.add_shortcut",auth()->user()->id)}}";

                    url = url+"?option="+option.id;

                    try {
                        let res = await axios.get(url);

                        this.user = res.data.data;

                        this.getData();
                        iziTs(res.data.message);
                        logI(res);

                    }catch (e) {
                        if(e.response.data){
                            logI(e.response.data);
                            iziTe(e.response.data.message);
                        }else{
                            logI(e);
                        }

                    }
                },
                async removeShortcut(option,index){

                    logI('remove shortcut',option,index);

                    let url = "{{route("api.users.remove_shortcut",auth()->user()->id)}}";

                    url = url+"?option="+option.id;

                    try {
                        let res = await axios.get(url);

                        iziTs(res.data.message);
                        this.user.shortcuts.splice(index,1);
                        logI(res);

                    }catch (e) {


                        if(e.response.data){
                            logI(e.response.data);
                            iziTe(e.response.data.message);
                        }else{
                            logI(e);
                        }

                    }
                }
            }
        });

        $(function(){

            $( ".sortable" ).sortable({
                update: function( event, ui ) {

                    var  opciones=[];
                    $(this).find('li').each(function (index,elemet) {
                        opciones.push($(this).attr('id'));
                    });

                }
            }).disableSelection();

        });
    </script>

@endpush
