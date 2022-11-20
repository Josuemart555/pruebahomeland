@extends('layouts.app')

@section('title_page',__('Productos'))
@include('layouts.plugins.bootstrap_fileinput')

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
                            <div class="form-group col-sm-12">
                                {!! Form::label('name', 'Foto Producto:') !!}
                                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg, image/gif" id="foto_producto" @change="onSelectFile" >
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                {!! Form::label('codigo', 'Codigo:') !!}
                                <input type="text" class="form-control" v-model="editedItem.codigo" onkeypress="return checkCodigo(event)">
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('nombre', 'Nombre:') !!}
                                <input type="text" class="form-control" v-model="editedItem.nombre" onkeypress="return checkNombre(event)">
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('cantidad', 'Cantidad:') !!}
                                {!! Form::number('cantidad', null, ['class' => 'form-control', 'v-model' => 'editedItem.cantidad']) !!}
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('precio', 'Precio:') !!}
                                {!! Form::text('precio', null, ['class' => 'form-control', 'v-model' => 'editedItem.precio']) !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_ingresa', 'Fecha Ingresa:') !!}
                            <input type="date" class="form-control" name="fecha_ingresa" id="fecha_ingresa" onchange="validarFechaIngresoMayorVencimiento()" v-model="editedItem.fecha_ingresa" >
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fehca_vencimiento', 'Fecha Vencimiento:') !!}
                            <input type="date" class="form-control" name="fehca_vencimiento" id="fehca_vencimiento" onchange="validarFechaIngresoMayorVencimiento()" v-model="editedItem.fehca_vencimiento" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary " @click="cerrarModal()">
                        {{__('Cancelar')}}
                    </button>
                    <button type="button" class="btn btn-outline-success " @click="guardarProducto()" >
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

        $(document).ready(function() {

            // $("#fecha_ingresa").change( function(){
            //     s= $("#fecha_ingresa").val();
            //     console.log(s)
            //     var bits = s.split('/');
            //     var d = new Date(bits[2] + '/' + bits[0] + '/' + bits[1]);
            //     alert(d);
            // });

            // $('#fecha_ingresa').change(function() {
            //     s= $("#fecha_ingresa").val();
            //     var bits = s.split('-');
            //     console.log(bits)
            //     var d = new Date(bits[1] + '/' + bits[2] + '/' + bits[0]);
            //     console.log(d);
            //     if (d == 'Invalid Date') {
            //         iziTw('La fehca ingresa es invalida!!!');
            //         Swal.close();
            //         return;
            //     }
            // });

        });

        function checkCodigo(e) {
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla == 8) {
                return true;
            }

            // Patrón de entrada, en este caso solo acepta numeros y letras
            patron = /[A-Za-z0-9]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }

        function checkNombre(e) {
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla == 8) {
                return true;
            }

            // Patrón de entrada, en este caso solo acepta letras
            patron = /^[a-zA-Z]+$/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }

        function validarFechaIngresoMayorVencimiento() {

            if (validarFormatoFecha($('#fecha_ingresa').val()) == 'Invalid Date') {
                iziTw('La fehca ingresa es invalida!!!');
                Swal.close();
                return;
            }

            if ($('#fecha_ingresa').val() && $('#fehca_vencimiento').val()) {
                if(Date.parse($('#fecha_ingresa').val()) > Date.parse($('#fehca_vencimiento').val())){
                    iziTw('La fehca ingresa no puede ser mayor a la vencimiento!!!');
                    return;
                }
            }
        }

        function validarFormatoFecha(campo) {
            const bits = campo.split('-');
            const d = new Date(bits[1] + '/' + bits[2] + '/' + bits[0]);
            return d;
        }

        const app = new Vue({
            el: '#vmProducto',
            created() {
                this.getData();
            },
            data: {
                productosLts : [],

                editedItem: {
                    id : 0,
                },

                defaultItem: {
                    id : 0,
                },

            },
            methods: {
                async getData(){
                    this.productosLts= [];

                    try {

                        var res = await axios.get(route('api.productos.index'));

                        this.productosLts = res.data.data;

                        logI(res);

                    } catch (e) {
                        if(e.response.data){
                            logI(e.response.data);
                        }else{
                            logI(e);
                        }

                    }
                },
                newProducto(){
                    setTimeout(() => {
                        this.editedItem = Object.assign({}, this.defaultItem);
                        this.inicializarFileInput(null, 'clear');
                        $("#modal-producto").modal('show');
                    }, 300);
                },
                cerrarModal() {
                    setTimeout(() => {
                        this.editedItem = Object.assign({}, this.defaultItem);
                        // this.inicializarFileInput(null, 'clear');
                        $("#modal-producto").modal('hide');
                    }, 300);
                },
                editShortcut(){
                    $("#modal-producto").modal('show');
                },
                onSelectFile(e){

                    this.editedItem.foto_producto = e.target.files[0];

                },
                inicializarFileInput(urlInitPre, metodo) {

                    $("#foto_producto").fileinput(metodo).fileinput({
                        language: "es",
                        initialPreview: urlInitPre,
                        dropZoneEnabled: true,
                        maxFileCount: 1,
                        maxFileSize: 1500,
                        showUpload: false,
                        initialPreviewAsData: true,
                        showBrowse: true,
                        showRemove: true,
                        theme: "fa",
                    });

                },
                async guardarProducto() {

                    Swal.fire({
                        title: 'Espera por favor...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timerProgressBar: true,
                    });
                    Swal.showLoading();

                    const data = this.editedItem;

                    console.log(data)

                    const formData = new FormData();

                    Object.entries(data).map(([campo, valor], i) => {
                        if (valor) {
                            formData.append(campo, valor);
                        }
                    });

                    const config = {
                        headers: {'content-type': 'multipart/form-data'}
                    }

                    try {

                        if(this.editedItem.id === 0){

                            let productoEncontrado = this.productosLts.find(obj => obj.codigo == this.editedItem.codigo);

                            if (productoEncontrado) {
                                iziTw('El Codigo ingresado ya existe!!!');
                                Swal.close();
                                return;
                            }

                            console.log(formData)

                            // var res = await axios.post(route('api.productos.store'), formData, config);

                            // this.cerrarModal();
                            Swal.close();

                        } else {

                            this.cerrarModal();
                            Swal.close()

                        }

                    } catch (e) {
                        notifyErrorApi(e);
                        Swal.close()
                    }

                },
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
