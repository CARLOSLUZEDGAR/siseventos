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
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Modals & Alerts</li>
            </ol>
          </div> -->
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
                          <th style="width: 65%" class="text-center">CLASIFICACIÓN</th>
                          <!-- <th style="width: 40%" class="text-center">TIPO</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(a, index) in ArrayClasificacion">
                          <td class="text-center">{{index + 1}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <button  type="button" class="btn btn-primary btn-sm" @click="Datos(a.id)">
                                    <i class="fas fa-edit"></i> EDITAR
                                </button>
                                <!-- <button type="button" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> VER
                                </button>
                                <button type="button" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> ELIMINAR
                                </button> -->
                            </td>                            
                            <td class="text-center" style="vertical-align: middle">{{a.clasificacion}}</td>
                            <!-- <td class="text-center" style="vertical-align: middle">{{a.precio}}</td> -->
                            <!-- <td class="text-center" style="vertical-align: middle;">
                                <div v-if="u.estado === 1">
                                <span class="badge badge-success">Habilitado</span>
                                </div>
                                <div v-else>
                                <span class="badge badge-danger">Desabilitado</span>                                            
                                </div>                            
                            </td>  -->
                        </tr>
                    </tbody>
                    
                </table> 
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
                                <input type="text" class="form-control" v-model="clasificacion" :class="{ 'is-invalid' : $v.clasificacion.$error, 'is-valid':!$v.clasificacion.$invalid }">
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
                                <input type="text" class="form-control" v-model="clasificacionE" :class="{ 'is-invalid' : $v.clasificacionE.$error, 'is-valid':!$v.clasificacionE.$invalid }">
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
                <button v-if="modal == 0" type="button" class="btn btn-primary" @click="Guardar()">GUARDAR</button>
                <button v-else type="button" class="btn btn-primary" @click="Editar()">EDITAR</button>
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
        }
    },

    validations: { 
        // modulo: { required },
        clasificacion: { required },
        clasificacionE: { required },
        // detalle: { required },

        validationsGroupReg: [
            'clasificacion',
            // 'detalle',
            // 'modulo',
        ],

        validationsGroupMod: [
            'clasificacionE',
            // 'detalle',
            // 'modulo',
        ],
    },

    mounted() {
      this.ListarClasificacion(1)
    },
    computed:{
        isActived: function(){
            return this.pagination.current_page;
        },
        //Calcuar los elementos de la paginacion
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
            
        }
    },
};
</script>

<style>
</style>