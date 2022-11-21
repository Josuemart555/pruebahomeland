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

                    <div class="table-responsive mb-0">
                        <table class="table table-bordered table-sm table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Fecha Ingreso</th>
                                <th>Fecha Vencimiento</th>
                                <th>
                                    <a href="#" @click.prevent="ordenarDescAsc()" >
                                        Fecha Creación
                                        <i v-show="!orderCreatedPrimeraVez" class="fa fa-arrow-up"></i>
                                        <i v-show="orderCreatedPrimeraVez" class="fa fa-arrow-down"></i>
                                    </a>
                                </th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="productosLts.length == 0">
                                <td colspan="10" class="text-center">Ningún Registro agregado</td>
                            </tr>
                            <tr v-for="producto in productosLts">
                                <td>
                                    <span v-if="producto.media_foto">
                                        <div class="images" v-viewer="{movable: false, modal: true}">
                                            <img class="img-thumbnail" :src="producto.media_foto.original_url" :key="producto.media_foto.original_url" style="height:90px;">
                                        </div>
                                    </span>
                                </td>
                                <td>
                                    <span v-text="producto.codigo"></span>
                                </td>
                                <td>
                                    <span v-text="producto.nombre"></span>
                                </td>
                                <td>
                                    <span v-text="producto.cantidad"></span>
                                </td>
                                <td>
                                    <span v-text="producto.precio"></span>
                                </td>
                                <td>
                                    <span v-text="formatDate(producto.fecha_ingresa)"></span>
                                </td>
                                <td>
                                    <span v-text="formatDate(producto.fehca_vencimiento)"></span>
                                </td>
                                <td>
                                    <span v-text="formatDate(producto.created_at)"></span>
                                </td>
                                <td  class="text-nowrap">
                                    <button type="button" @click="editarProducto(producto)" class='btn btn-outline-info btn-sm' v-tooltip="'Editar'" >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" @click="eliminarProducto(producto)" class='btn btn-outline-danger btn-sm' v-tooltip="'ELiminar'" >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="10" >
                                    <ul class="pagination ">

                                        <li class="page-item" v-for="index in calcularPaginacion">
                                            <a class="page-link" href="#" @click.prevent="paginacionSeleccionado(index)" >
                                                <span v-text="index"></span>
                                            </a>
                                        </li>

                                    </ul>
                                </td>
                            </tr>
                            </tfoot>
{{--                            <tfoot>--}}
{{--                                <tr>--}}
{{--                                    <ul class="pagination ">--}}
{{--                                        <li class="page-item disabled" aria-disabled="true">--}}
{{--                                            <span class="page-link">@lang('pagination.previous')</span>--}}
{{--                                        </li>--}}

{{--                                        <li class="page-item">--}}
{{--                                            <a class="page-link" href="#"--}}
{{--                                               rel="prev">@lang('pagination.previous')</a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </tr>--}}
{{--                            </tfoot>--}}
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script>

        $(document).ready(function() {


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

                this.getData2();
                this.calcularPaginacion;
            },
            data: {
                productosLts : [],
                productosLts2 : [],

                editedItem: {
                    id : 0,
                },

                defaultItem: {
                    id : 0,
                },

                orderCreated: null,
                orderCreatedPrimeraVez: true,

                numeroPaginacion: 0,

            },
            methods: {
                async getData(){
                    this.productosLts= [];

                    try {

                        const data = {};
                        data.orderable = this.orderCreated;
                        data.limit = 5;
                        data.offset = this.numeroPaginacion;

                        console.log('data',data)

                        var res = await axios.get(route('api.productos.index', data));

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
                async getData2(){
                    this.productosLts2= [];

                    try {

                        var res = await axios.get(route('api.productos.index'));

                        this.productosLts2 = res.data.data;

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
                        this.inicializarFileInput(null, 'clear');
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

                    let res;

                    const data = this.editedItem;

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

                            res = await axios.post(route('api.productos.store'), formData, config);
                            this.cerrarModal();

                            Swal.close();

                        } else {

                            formData.delete('media_foto');
                            formData.delete('media');

                            formData.append('_method', 'PATCH');

                            res = await axios.post(route('api.productos.update', this.editedItem.id), formData, config);
                            this.cerrarModal();

                            Swal.close()

                        }

                        iziTs(res.data.message);
                        await this.getData();

                    } catch (e) {
                        notifyErrorApi(e);
                        Swal.close()
                    }

                },
                editarProducto(item) {
                    setTimeout(() => {
                        this.editedItem = Object.assign({}, item);
                        this.editedItem.fecha_ingresa = this.formattedDate(this.editedItem.fecha_ingresa);
                        this.editedItem.fehca_vencimiento = this.formattedDate(this.editedItem.fehca_vencimiento);

                        if (item.media_foto) {
                            this.inicializarFileInput(item.media_foto.original_url, 'destroy');
                        } else {
                            this.inicializarFileInput('', 'destroy');
                        }

                        $("#modal-producto").modal('show');
                    }, 300);
                },
                async eliminarProducto(item) {

                    let confirm = await Swal.fire({
                        title: '¿Estás seguro de eliminar?',
                        text: "¡No podrás revertir esto!",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, eliminar\n!'
                    });

                    if (confirm.isConfirmed) {
                        try{

                            Swal.fire({
                                title: 'Espera por favor...',
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                timerProgressBar: true,
                            });
                            Swal.showLoading();

                            let res = await axios.delete(route('api.productos.destroy',item.id))
                            Swal.close();

                            iziTs(res.data.message);
                            await this.getData();

                        }catch (e){
                            notifyErrorApi(e);
                            Swal.close();
                        }
                    }

                },
                formatDate(inputDate) {
                    let date, month, year;

                    inputDate = new Date(inputDate);

                    date = inputDate.getDate();
                    month = inputDate.getMonth() + 1;
                    year = inputDate.getFullYear();

                    date = date
                        .toString()
                        .padStart(2, '0');

                    month = month
                        .toString()
                        .padStart(2, '0');

                    return `${date}/${month}/${year}`;
                },
                formatDate2(inputDate) {

                    inputDate = new Date(inputDate);

                    const format = 'yyyy-MM-dd';
                    const locale = 'en-US';
                    const formatteDate = formatDate(inputDate, format, locale);
                    return formatteDate;

                },
                formattedDate(fecha) {
                    return moment(fecha, 'YYYY-MM-DD').format('yyyy-MM-D') ?? '';
                },
                async ordenarDescAsc() {

                    if (this.orderCreatedPrimeraVez) {
                        this.orderCreated = 'desc';
                        this.orderCreatedPrimeraVez = false;
                        await this.getData();
                    } else {
                        this.orderCreated = 'asc';
                        this.orderCreatedPrimeraVez = true;
                        await this.getData();
                    }

                },
                async paginacionSeleccionado(index) {

                    let resultado = 0;
                    if (index == 1) {
                        resultado = 0;
                    } else {
                        // el 5 es el total de resultados muestra que es por defecto
                        resultado = (5 * (index - 1));
                    }

                    this.numeroPaginacion = resultado;
                    await this.getData();
                }
            },
            computed: {
                calcularPaginacion() {
                    const resultado = (this.productosLts2.length / 5)
                    console.log(parseInt(resultado.toFixed()));
                    return parseInt(resultado.toFixed());
                }
            },
        });

    </script>

@endpush
