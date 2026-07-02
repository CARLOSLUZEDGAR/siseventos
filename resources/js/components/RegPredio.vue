<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">            
            <h1>
              <i class="fas fa-user-tag"></i> &nbsp;
                PREDIO
            </h1>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list-ol"></i> &nbsp;
                  BUSCAR PREDIO
                </h3>
              </div>
              <div class="card-body">                  
                    <div class="row d-flex justify-content-center"> 
                        <div class="col-md-4">
                            <input type="text" class="form-control" style="text-transform:uppercase;" @keyup="BuscarPredio()" v-model="buscar">
                        </div>
                    </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- ./row -->

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list-ol"></i> &nbsp;
                  LISTA DE PREDIOS &nbsp;&nbsp;
                    <button type="button" class="btn btn-success btn-sm" @click="Nuevo()">
                        <i class="fas fa-plus"></i> NUEVO
                    </button> 
                </h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                          <th style="width: 5%" class="text-center">#</th>
                          <th class="text-center">OPCIONES</th>
                          <th class="text-center">CLASIFICACIÓN</th>
                          <th class="text-center">PREDIO</th>
                          <th class="text-center">COLOR</th>
                          <th class="text-center">ALQUILER (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(a, index) in ArrayPredio">
                          <td class="text-center" style="vertical-align: middle">{{index + 1}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <template v-if="a.estado == 1">
                                    <button  type="button" class="btn btn-primary btn-sm" @click="Datos(a.id)">
                                        <i class="fas fa-edit"></i>&nbsp;EDITAR
                                    </button>
                                </template>
                                <template v-if="a.estado == 1">
                                    <button type="button" class="btn btn-danger btn-sm" @click="CambiarEstado(a.id, a.estado)" :disabled="procesando">
                                        <i class="fas fa-toggle-off"></i>&nbsp;{{ procesando ? 'Procesando...' : 'DESHABILITAR' }}
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-success btn-sm" @click="CambiarEstado(a.id, a.estado)" :disabled="procesando">
                                        <i class="fas fa-toggle-on"></i>&nbsp;{{ procesando ? 'Procesando...' : 'HABILITAR' }}
                                    </button>
                                </template>
                            </td>                            
                            <td class="text-center" style="vertical-align: middle">{{a.clasificacion}}</td>
                            <td class="text-justify" style="vertical-align: middle">{{a.nombre}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <span
                                    :style="{
                                        backgroundColor: a.color,
                                        display: 'inline-block',
                                        width: '20px',
                                        height: '20px',
                                        borderRadius: '50%',
                                        border: '1px solid #666'
                                    }"
                                ></span>
                            </td>
                            <td class="text-center" style="vertical-align: middle">{{a.precio}}</td>
                        </tr>
                    </tbody>
                    
                </table><br>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                        </li>
                        
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                        </li>
                    </ul>
                </nav>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- ./row -->
      </div>
      <!-- /.container-fluid -->

        <div class="modal fade"  data-backdrop="static" id="ModalPredio">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <template v-if="modal == 0">
                        <h4 class="modal-title">NUEVO PREDIO</h4>
                    </template>
                    <template v-else>
                        <h4 class="modal-title">EDITAR PREDIO</h4>
                    </template>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <template v-if="modal == 0">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">PREDIO:</label>
                                <input type="text" class="form-control" v-model="predio" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.predio.$error, 'is-valid':!$v.predio.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.predio.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">ABREVIATURA:</label>
                                <input type="text" class="form-control" v-model="abreviatura" style="text-transform:uppercase;">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>TIPO PREDIO:</label>
                                <select
                                    class="form-control"
                                    v-model="tipo_predio"
                                    :class="{
                                        'is-invalid': $v.tipo_predio.$error,
                                        'is-valid': !$v.tipo_predio.$invalid
                                    }"
                                >
                                    <option value="">SELECCIONE...</option>
                                    <option
                                        v-for="item in arrayTipoPredios"
                                        :key="item.id"
                                        :value="item"
                                    >
                                        {{ item.clasificacion }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.tipo_predio.required">
                                        Este campo es requerido
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">COLOR:</label>
                                <input type="color" class="form-control" v-model="color" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.color.$error, 'is-valid':!$v.color.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.color.required">Este campo es Requerido</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-control-label">
                                    PRECIO (Bs.):
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control" v-model.number="precio" :class="{'is-invalid' : $v.precio.$error, 'is-valid': !$v.precio.$invalid}">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.precio.required">
                                        Este campo es requerido
                                    </span>
                                    <span v-if="$v.precio.required && !$v.precio.decimalDos">
                                        Debe ingresar un precio válido con máximo 2 decimales
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">OBSERVACIÓN:</label>
                                <textarea class="form-control" v-model="observacion" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">PREDIO:</label>
                                <input type="text" class="form-control" v-model="predioE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.predioE.$error, 'is-valid':!$v.predioE.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.predioE.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">ABREVIATURA:</label>
                                <input type="text" class="form-control" v-model="abreviaturaE" style="text-transform:uppercase;">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>TIPO PREDIO:</label>
                                <input type="text" class="form-control" v-model="tipo_predioE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.tipo_predioE.$error, 'is-valid':!$v.tipo_predioE.$invalid }" disabled>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.tipo_predioE.required">
                                        Este campo es requerido
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">COLOR:</label>
                                <input type="color" class="form-control" v-model="colorE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.colorE.$error, 'is-valid':!$v.colorE.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.colorE.required">Este campo es Requerido</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-control-label">
                                    PRECIO (Bs.):
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control" v-model.number="precioE" :class="{'is-invalid' : $v.precioE.$error, 'is-valid': !$v.precioE.$invalid}">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.precioE.required">
                                        Este campo es requerido
                                    </span>
                                    <span v-if="$v.precioE.required && !$v.precioE.decimalDos">
                                        Debe ingresar un precio válido con máximo 2 decimales
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">OBSERVACIÓN:</label>
                                <textarea class="form-control" v-model="observacionE" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar()">CANCELAR</button>
                    <button v-if="modal == 0" type="button" class="btn btn-primary" @click="Guardar()" :disabled="procesando">{{ procesando ? 'Procesando...' : 'GUARDAR' }}</button>
                    <button v-else type="button" class="btn btn-primary" @click="Editar()" :disabled="procesando">{{ procesando ? 'Procesando...' : 'EDITAR' }}</button>
                </div>

            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
import { required, requiredIf, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";

const decimalDos = value => {
    if (!value) return true;

    return /^\d+(\.\d{1,2})?$/.test(value);
};

export default {
    data() {
        return {
          /**
           * Variables de recepcion 
           */
          modal: '',
          predio: '',
          abreviatura: '',
          tipo_predio: '',
          color: '',
          precio: '',
          observacion: '',

          ArrayPredio: [],
          ArrayMostrarPredio: [],
          arrayTipoPredios: [],

          idE: '',
          predioE: '',
          abreviaturaE: '',
          tipo_predioE: '',
          colorE: '',
          precioE: '',
          observacionE: '',

          /**
          * Variables paginacion
          */
          pagination : {
              'total' : 0,
              'current_page' : 0,
              'per_page' : 0,
              'last_page' : 0,
              'from' : 0,
              'to' : 0,
          },
          offset : 3,
          code: "",
          /**
           * BUSCADOR
           */

          buscar: '',
          setTiemoutBuscador: '',

          procesando: false,
        }
    },

    validations: { 
        predio: { required },
        tipo_predio: { required },
        color: { required },
        precio: { required, decimalDos },

        predioE: { required },
        tipo_predioE: { required },
        colorE: { required },
        precioE: { required, decimalDos },

        validationsGroupReg: [
            'predio',
            'tipo_predio',
            'color',
            'precio',
        ],

        validationsGroupMod: [
            'predioE',
            'tipo_predioE',
            'colorE',
            'precioE',
        ],
    },

    mounted() {
      this.ListarPredio(1);
      this.ListarTipoPredio();
    },
    computed:{
        isActived: function(){
            return this.pagination.current_page;
        },
        //Calcular los elementos de la paginacion
        pagesNumber: function() {
        if(!this.pagination.to){
            return [];
        }
        var from = this.pagination.current_page - this.offset;
        if(from < 1){
            from = 1;
        }
        var to = from + (this.offset *2);
        if( to >= this.pagination.last_page){
            to = this.pagination.last_page;
        }
        var pagesArray = [];
        while( from <= to){
            pagesArray.push(from);
            from ++;
        }
        return pagesArray;
        },
    },
    methods: {
        cambiarPagina(page){
            let me = this;
            me.pagination.current_page = page;
            me.ListarPredio(page);
        },

        ListarTipoPredio() {
            let me = this;
            axios
            .post("/listarTipoPredio", {
            })
            .then(function (response) {
            me.arrayTipoPredios = response.data.tipo_predios
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarPredio(page){
            let me = this;
            axios
            .post("/buscarPredio", {
                buscar: me.buscar.toUpperCase(),
                page: page
            })
            .then(function (response) {
                me.ArrayPredio = response.data.predios.data;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        },

        BuscarPredio(){
            clearTimeout(this.setTiemoutBuscador);
            this.setTiemoutBuscador = setTimeout(() => {
                this.ListarPredio(1)
            }, 360)
        },

        Nuevo(){
            this.$v.validationsGroupReg.$reset(),
            this.modal = 0;
            // this.ListarModulos();
            $('#ModalPredio').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },
      
        Datos(id){
            let me = this;
            this.$v.validationsGroupMod.$reset(),
            me.modal = 1;
            // me.ListarModulos();
            axios
                .post("/mostrarPredio", {
                    id: id
                })
                .then((response) => {
                    me.ArrayMostrarPredio = response.data.predios;
                    me.idE = me.ArrayMostrarPredio.id;
                    me.predioE = me.ArrayMostrarPredio.nombre;
                    me.abreviaturaE = me.ArrayMostrarPredio.abreviatura;
                    me.tipo_predioE = me.ArrayMostrarPredio.clasificacion;
                    me.colorE = me.ArrayMostrarPredio.color;
                    me.precioE = me.ArrayMostrarPredio.precio;
                    me.observacionE = me.ArrayMostrarPredio.observacion;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            $('#ModalPredio').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },

        Cerrar(){
            this.predio = '';
            this.abreviatura = '';
            this.tipo_predio = '';
            this.color = '';
            this.precio = '';
            this.observacion = '';

            this.idE = '';
            this.predioE = '';
            this.abreviaturaE = '';
            this.tipo_predioE = '';
            this.colorE = '';
            this.precioE = '';
            this.observacionE = '';
        },

        Guardar() {
            if (this.procesando) {
                return;
            }
            if (!this.$v.validationsGroupReg.$invalid) {
                swal.fire({
                    title: '¿Desea registrar este predio?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'info',
                    cancelButtonColor: '#868077',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    buttonsStyling: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        this.procesando = true;
                        axios.post('/registrarPredio', {
                            predio: this.predio.toUpperCase(),
                            abreviatura: this.abreviatura
                                ? this.abreviatura.toUpperCase()
                                : '',
                            tipo_predio: this.tipo_predio.id,
                            color: this.color,
                            precio: this.precio,
                            observacion: this.observacion
                                ? this.observacion.toUpperCase()
                                : ''
                        })
                        .then((response) => {
                            this.procesando = false;
                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });
                                $('#ModalPredio').modal('hide');
                                this.Cerrar();
                                this.ListarPredio();
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ADVERTENCIA',
                                    text: response.data.mensaje
                                });
                            }
                        })
                        .catch((error) => {
                            this.procesando = false;
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'ERROR',
                                text: 'Ocurrió un error inesperado'
                            });
                        });
                    } else {
                        swal.fire(
                            'Información',
                            'Solicitud cancelada.',
                            'info'
                        );
                        $('#ModalPredio').modal('hide');
                        this.Cerrar();
                    }
                });
            } else {
                this.$v.validationsGroupReg.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },

        Editar(){
            if (this.procesando) {
                return;
            }
            if(!this.$v.validationsGroupMod.$invalid){
                swal.fire({
                    title: '¿Desea editar este predio?', // TITULO 
                    icon: 'question', //ICONO (success, warnning, error, info, question)
                    showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
                    confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
                    cancelButtonColor: '#868077', // CLOR DEL BOTON CANCELAR
                    confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
                    cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
                    buttonsStyling: true,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        this.procesando = true;
                        axios.post('/editarPredio', {
                            id_predio: this.idE,
                            predio: this.predioE.toUpperCase(),
                            abreviatura: this.abreviaturaE
                                ? this.abreviaturaE.toUpperCase()
                                : '',
                            color: this.colorE,
                            precio: this.precioE,
                            observacion: this.observacionE
                                ? this.observacionE.toUpperCase()
                                : ''
                        })
                        .then((response) => {
                            if(response.data.success){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });
                                $('#ModalPredio').modal('hide');
                                this.Cerrar();
                                this.ListarPredio();
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ADVERTENCIA',
                                    text: response.data.mensaje
                                });
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'ERROR',
                                text: 'Ocurrió un error inesperado'
                            });
                        })
                        .finally(() => {
                            this.procesando = false;
                        });
                    }else{
                        let me = this;
                        swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        $('#ModalPredio').modal('hide');
                        me.Cerrar();
                    }
                })
            }else{
                this.$v.validationsGroupMod.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                }) 
            }
        },

        CambiarEstado(id, estado){
            if (this.procesando) {
                return;
            }
            if (estado == 1) {
                var titulo = '¿Desea deshabilitar este predio?';
                // var titulo2 = 'Usuario deshabilitado correctamente';
            } else {
                var titulo = '¿Desea habilitar este predio?';
                // var titulo2 = 'Usuario habilitado correctamente';
            }
            swal.fire({
                title: titulo, // TITULO 
                icon: 'question', //ICONO (success, warnning, error, info, question)
                showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
                confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
                cancelButtonColor: '#868077', // CLOR DEL BOTON CANCELAR
                confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
                cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    this.procesando = true;
                    axios.post('/cambiarEstadoPredio', {
                        id_predio: id,
                        estado: estado
                    })
                    .then((response) => {
                        if(response.data.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'CORRECTO',
                                text: response.data.mensaje
                            });
                            this.ListarPredio();
                        }else{
                            Swal.fire({
                                icon: 'warning',
                                title: 'ADVERTENCIA',
                                text: response.data.mensaje
                            });
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            text: 'Ocurrió un error inesperado'
                        });
                    })
                    .finally(() => {
                        this.procesando = false;
                    });
                }else{
                    let me = this;
                    swal.fire(
                        "Informacion", //TITULO
                        "Solicitud cancelada.", //TEXTO DE MENSAJE
                        "info" // TIPO DE MODAL (success, warnning, error, info)
                    );
                }
            })
        }
    },
};
</script>

<style>
</style>