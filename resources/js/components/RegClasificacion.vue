<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">            
            <h1>
              <i class="fas fa-user-tag"></i> &nbsp;
              CLASIFICACIÓN
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
                  BUSCAR CLASIFICACIÓN
                </h3>
              </div>
              <div class="card-body">                  
                    <div class="row d-flex justify-content-center"> 
                        <div class="col-md-4">
                            <input type="text" class="form-control" @keyup="BuscarClasificacion()" v-model="buscar">
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
                  LISTA DE CLASIFICACIÓN &nbsp;&nbsp;
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
                          <th style="width: 30%" class="text-center">OPCIONES</th>
                          <th style="width: 30%" class="text-center">CLASIFICACIÓN</th>
                          <th style="width: 35%" class="text-center">DETALLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(a, index) in ArrayClasificacion">
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
                            <td class="text-center" style="vertical-align: middle">{{a.clasificacion}}</td>
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

        <div class="modal fade"  data-backdrop="static" id="ModalClasificacion">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <template v-if="modal == 0">
                        <h4 class="modal-title">NUEVA CLASIFICACIÓN</h4>
                    </template>
                    <template v-else>
                        <h4 class="modal-title">EDITAR CLASIFICACIÓN</h4>
                    </template>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <template v-if="modal == 0">
                            <div class="col-md-12">
                                <label class="form-control-label" for="text-input">CLASIFICACIÓN:</label>
                                <input type="text" class="form-control" v-model="clasificacion" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.clasificacion.$error, 'is-valid':!$v.clasificacion.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.clasificacion.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="">DETALLE:</label>
                                <textarea class="form-control" v-model="detalle" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                            </div>
                        </template>
                        <template v-else>
                            <div class="col-md-12">
                                <label class="form-control-label" for="text-input">CLASIFICACIÓN:</label>
                                <input type="text" class="form-control" v-model="clasificacionE" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.clasificacionE.$error, 'is-valid':!$v.clasificacionE.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.clasificacionE.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="">DETALLE:</label>
                                <textarea class="form-control" v-model="detalleE" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                            </div>
                        </template>
                    </div>
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
export default {
    data() {
        return {
          /**
           * Variables de recepcion 
           */
          clasificacion: '',
          detalle: '',
          modal: '',

          ArrayClasificacion: [],
          ArrayMostrarClasificacion: [],

          idE: '',
          clasificacionE: '',
          detalleE: '',

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
        clasificacion: { required },
        clasificacionE: { required },

        validationsGroupReg: [
            'clasificacion',
        ],

        validationsGroupMod: [
            'clasificacionE',
        ],
    },

    mounted() {
      this.ListarClasificacion(1)
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
            me.ListarClasificacion(page);
        },

        ListarClasificacion(page){
            let me = this;
            axios
            .post("/buscarClasificacion", {
                buscar: me.buscar.toUpperCase(),
                page: page
            })
            .then(function (response) {
                me.ArrayClasificacion = response.data.tipo_predios.data;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            
        },

        BuscarClasificacion(){
            clearTimeout(this.setTiemoutBuscador);
            this.setTiemoutBuscador = setTimeout(() => {
                this.ListarClasificacion(1)
            }, 360)
        },

        Nuevo(){
            this.$v.validationsGroupReg.$reset(),
            this.modal = 0;
            // this.ListarModulos();
            $('#ModalClasificacion').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },
      
        Datos(id){
            let me = this;
            this.$v.validationsGroupMod.$reset(),
            me.modal = 1;
            // me.ListarModulos();
            axios
                .post("/mostrarClasificacion", {
                    id: id
                })
                .then((response) => {
                    me.ArrayMostrarClasificacion = response.data.tipo_predios;
                    me.idE = me.ArrayMostrarClasificacion.id;
                    me.clasificacionE = me.ArrayMostrarClasificacion.clasificacion;
                    me.detalleE = me.ArrayMostrarClasificacion.observacion;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            $('#ModalClasificacion').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },

        Cerrar(){
            this.clasificacion = '';
            this.detalle = '';
            this.modal = '';
            this.idE = '';
            this.clasificacionE = '';
            this.detalleE = '';
        },

        Guardar() {
            if (this.procesando) {
                return;
            }
            if (!this.$v.validationsGroupReg.$invalid) {
                swal.fire({
                    title: '¿Desea registrar esta clasificacion?',
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
                        axios.post('/registrarClasificacion', {
                            clasificacion: this.clasificacion.toUpperCase(),
                            observacion: this.detalle
                                ? this.detalle.toUpperCase()
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
                                $('#ModalClasificacion').modal('hide');
                                this.Cerrar();
                                this.ListarClasificacion();
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
                        $('#ModalClasificacion').modal('hide');
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
                    title: '¿Desea editar esta clasificación?', // TITULO 
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
                        axios.post('/editarClasificacion', {
                            id_clasificacion: this.idE,
                            clasificacion: this.clasificacionE.toUpperCase(),
                            observacion: this.detalleE
                                ? this.detalleE.toUpperCase()
                                : ''
                        })
                        .then((response) => {
                            if(response.data.success){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });
                                $('#ModalClasificacion').modal('hide');
                                this.Cerrar();
                                this.ListarClasificacion();
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
                        $('#ModalClasificacion').modal('hide');
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
                var titulo = '¿Desea deshabilitar esta clasificación?';
                // var titulo2 = 'Usuario deshabilitado correctamente';
            } else {
                var titulo = '¿Desea habilitar esta clasificación?';
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
                    axios.post('/cambiarEstadoClasificacion', {
                        id_clasificacion: id,
                        estado: estado
                    })
                    .then((response) => {
                        if(response.data.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'CORRECTO',
                                text: response.data.mensaje
                            });
                            this.ListarClasificacion();
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