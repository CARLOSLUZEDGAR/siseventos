<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">            
            <h1>
              <i class="fas fa-user-tag"></i> &nbsp;
                TARIFA
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
                  BUSCAR TARIFA
                </h3>
              </div>
              <div class="card-body">                  
                    <div class="row d-flex justify-content-center"> 
                        <div class="col-md-4">
                            <input type="text" class="form-control" @keyup="BuscarTarifa()" v-model="buscar">
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
                  LISTA DE TARIFAS &nbsp;&nbsp;
                    <button type="button" class="btn btn-success btn-sm" @click="Nuevo()">
                        <i class="fas fa-plus"></i> NUEVO
                    </button> 
                </h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                          <th style="" class="text-center">#</th>
                          <th style="" class="text-center">OPCIONES</th>
                          <th style="" class="text-center">TARIFA</th>
                          <th style="" class="text-center">% DESCUENTO</th>
                          <th style="" class="text-center">DETALLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(a, index) in ArrayTarifa">
                          <td class="text-center" style="vertical-align: middle">{{index + 1}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <button  type="button" class="btn btn-primary btn-sm" @click="Datos(a.id)">
                                    <i class="fas fa-edit"></i>&nbsp;EDITAR
                                </button>
                                <!-- <button type="button" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> VER
                                </button> -->
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
                            <td class="text-center" style="vertical-align: middle">{{a.tarifa}}</td>
                            <td class="text-center" style="vertical-align: middle">{{a.porcentaje}}</td>
                            <td class="text-justify" style="vertical-align: middle">{{a.observacion}}</td>
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

        <div class="modal fade"  data-backdrop="static" id="ModalTarifa">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <template v-if="modal == 0">
                        <h4 class="modal-title">NUEVO TARIFA</h4>
                    </template>
                    <template v-else>
                        <h4 class="modal-title">EDITAR TARIFA</h4>
                    </template>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <template v-if="modal == 0">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">TARIFA:</label>
                                <input type="text" class="form-control" v-model="tarifa" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.tarifa.$error, 'is-valid':!$v.tarifa.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.tarifa.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">DESCUENTO (%):</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" v-model.number="porcentaje" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.porcentaje.$error, 'is-valid':!$v.porcentaje.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.porcentaje.required">Este campo es Requerido</span>
                                    <span v-if="$v.porcentaje.required && !$v.porcentaje.decimalDos">
                                        Debe ingresar un precio válido con máximo 2 decimales
                                    </span>
                                    <span v-else-if="!$v.porcentaje.montoMaximo">
                                        El descuento no puede ser mayor a 100%.
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
                                <label class="form-control-label" for="text-input">TARIFA:</label>
                                <input type="text" class="form-control" v-model="tarifaE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.tarifaE.$error, 'is-valid':!$v.tarifaE.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.tarifaE.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">DESCUENTO (%):</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" v-model.number="porcentajeE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.porcentajeE.$error, 'is-valid':!$v.porcentajeE.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.porcentajeE.required">Este campo es Requerido</span>
                                    <span v-if="$v.porcentajeE.required && !$v.porcentajeE.decimalDos">
                                        Debe ingresar un precio válido con máximo 2 decimales
                                    </span>
                                    <span v-else-if="!$v.porcentajeE.montoMaximo">
                                        El descuento no puede ser mayor a 100%.
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

const montoMaximo = value => {

    if (!value) return true;

    // if (!this.situacion || this.situacion.id != 1) {
    //     return true;
    // }

    return Number(value) <= 100;
};

export default {
    data() {
        return {
          /**
           * Variables de recepcion 
           */
          modal: '',
          tarifa: '',
          porcentaje: '',
          observacion: '',

          ArrayTarifa: [],
          ArrayMostrarTarifa: [],

          idE: '',
          tarifaE: '',
          porcentajeE: '',
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
        tarifa: { required },
        porcentaje: { required, decimalDos, montoMaximo },

        tarifaE: { required },
        porcentajeE: { required, decimalDos, montoMaximo },

        validationsGroupReg: [
            'tarifa',
            'porcentaje',
        ],

        validationsGroupMod: [
            'tarifaE',
            'porcentajeE',
        ],
    },

    mounted() {
      this.ListarTarifa(1);
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
            me.ListarTarifa(page);
        },

        // ListarTipoPredio() {
        //     let me = this;
        //     axios
        //     .post("/listarTipoPredio", {
        //     })
        //     .then(function (response) {
        //     me.arrayTipoPredios = response.data.tipo_predios
        //     })
        //     .catch(function (error) {
        //     // handle error
        //     console.log(error);
        //     })
        // },

        ListarTarifa(page){
            let me = this;
            axios
            .post("/buscarTarifa", {
                buscar: me.buscar.toUpperCase(),
                page: page
            })
            .then(function (response) {
                me.ArrayTarifa = response.data.tarifas.data;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        },

        BuscarTarifa(){
            clearTimeout(this.setTiemoutBuscador);
            this.setTiemoutBuscador = setTimeout(() => {
                this.ListarTarifa(1)
            }, 360)
        },

        Nuevo(){
            this.$v.validationsGroupReg.$reset(),
            this.modal = 0;
            // this.ListarModulos();
            $('#ModalTarifa').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },
      
        Datos(id){
            let me = this;
            this.$v.validationsGroupMod.$reset(),
            me.modal = 1;
            // me.ListarModulos();
            axios
                .post("/mostrarTarifa", {
                    id: id
                })
                .then((response) => {
                    me.ArrayMostrarTarifa = response.data.tarifas;
                    me.idE = me.ArrayMostrarTarifa.id;
                    me.tarifaE = me.ArrayMostrarTarifa.tarifa;
                    me.porcentajeE = me.ArrayMostrarTarifa.porcentaje;
                    me.observacionE = me.ArrayMostrarTarifa.observacion;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            $('#ModalTarifa').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },

        Cerrar(){
            this.tarifa = '';
            this.porcentaje = '';
            this.observacion = '';

            this.idE = '';
            this.tarifaE = '';
            this.porcentajeE = '';
            this.observacionE = '';
        },

        Guardar() {
            if (this.procesando) {
                return;
            }
            if (!this.$v.validationsGroupReg.$invalid) {
                swal.fire({
                    title: '¿Desea registrar esta tarifa?',
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
                        axios.post('/registrarTarifa', {
                            tarifa: this.tarifa.toUpperCase(),
                            porcentaje: this.porcentaje,
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
                                $('#ModalTarifa').modal('hide');
                                this.Cerrar();
                                this.ListarTarifa();
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
                        $('#ModalTarifa').modal('hide');
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
                    title: '¿Desea editar esta tarifa?', // TITULO 
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
                        axios.post('/editarTarifa', {
                            id_tarifa: this.idE,
                            tarifa: this.tarifaE.toUpperCase(),
                            porcentaje: this.porcentajeE,
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
                                $('#ModalTarifa').modal('hide');
                                this.Cerrar();
                                this.ListarTarifa();
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
                        $('#ModalTarifa').modal('hide');
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
                var titulo = '¿Desea deshabilitar este evento?';
                // var titulo2 = 'Usuario deshabilitado correctamente';
            } else {
                var titulo = '¿Desea habilitar este evento?';
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
                    axios.post('/cambiarEstadoTipoEvento', {
                        id_tipo_evento: id,
                        estado: estado
                    })
                    .then((response) => {
                        if(response.data.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'CORRECTO',
                                text: response.data.mensaje
                            });
                            this.ListarTipoEvento();
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