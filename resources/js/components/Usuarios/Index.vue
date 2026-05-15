<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">            
            <h1>
              <i class="fas fa-users"></i> &nbsp;
              USUARIOS
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
                <div class="card card-dark card-outline">
                <div class="card-header">
                    <!--<h3 class="card-title">
                    <i class="fas fa-user-plus"></i> &nbsp;
                    REGISTRAR NUEVO USUARIO
                    </h3>-->
                    <button class="btn btn-primary" type="submit" @click="NuevoUsuario()">
                        <i class="fas fa-search"></i>&nbsp; REGISTRAR NUEVO USUARIO
                    </button>
                </div>
                <!--<div class="card-body">
                        <div class="row  d-flex justify-content-center">
                            <div class="col-md-8 ">
                                <div class="input-group">
                                    <div class="col-md-8">
                                        <v-select
                                            label="text"
                                            :options="Apersonal"
                                            v-model="per"
                                        >
                                            <template v-slot:no-options="{ search, searching }">
                                            <template v-if="searching">
                                                Lo sentimos, no hay opciones de coincidencia para <em>{{
                                                search
                                                }}</em
                                                >.
                                            </template>
                                            <em v-else
                                                >Lo sentimos, no hay opciones de coincidencia.</em
                                            >
                                            </template>
                                        </v-select>
                                    </div>    
                                    <button class="btn btn-primary" type="subnmit" @click="NuevoUsuarioModal()">
                                        <i class="fas fa-search"></i>&nbsp; BUSCAR
                                    </button>
                                </div>

                            </div>
                        </div>
                </div>-->
                <!-- /.card -->
                </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- ./row -->
            <div class="row">
            <div class="col-md-12">
                <div class="card card-dark card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                    <i class="far fa-list-alt"></i>&nbsp;
                    LISTA DE USUARIOS
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row d-flex justify-content-center"> 
                        <div class="col-md-4">
                            <label for="">BUSCAR USUARIO:</label>
                            <input type="text" style="text-transform:uppercase;" class="form-control" @keyup="BuscarUsuario(1)" v-model="buscar">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 20%" class="text-center">OPCIONES</th>
                                    <th style="width: 40%" class="text-center">NOMBRE COMPLETO</th>
                                    <!--<th style="width: 20%" class="text-center">CARNET MILITAR</th>-->
                                    <th style="width: 20%" class="text-center">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in Ausuarios">
                                    <td class="text-center">
                                        <button  type="button" class="btn btn-success btn-sm" @click="ModalRoles(u.usuario_id,u.user_id)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button  type="button" class="btn btn-primary btn-sm" @click="EditarModal(u.usuario_id,u.user_id)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button v-if="u.estado === 1" type="button" class="btn btn-danger btn-sm" @click="CambioEstado(u.estado,u.usuario_id,u.user_id)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-success btn-sm" @click="CambioEstado(u.estado,u.usuario_id,u.user_id)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                    <td>{{u.nombres}} {{u.ap_paterno}} {{u.ap_materno}}</td>
                                    <!--<td class="text-center">{{u.cm}}</td>-->
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div v-if="u.estado === 1">
                                        <span class="badge badge-success">Habilitado</span>
                                        </div>
                                        <div v-else>
                                        <span class="badge badge-danger">Desabilitado</span>                                            
                                        </div>                            
                                    </td> 
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
                    
                </div>
                <!-- /.card -->
                </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- ./row -->
        </div>
        <!-- /.container-fluid -->
      

        <!-- Seccion de Modals -->
        <!-- Modal Nuevo Usuario -->
        <div class="modal fade" data-backdrop="static" id="ModalUsuario">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title-registro"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar(1)">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="form-control-label" for="text-input">Nombre</label>
                        <input type="text" v-model="nom_usu" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.nom_usu.$error, 'is-valid':!$v.nom_usu.$invalid }">
                        <div class="invalid-feedback">
                            <span v-if="!$v.nom_usu.required">Este campo es Requerido</span>
                            <span v-else-if="!$v.nom_usu.letrasSpanish">Solo letras</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="text-input">Ap. Paterno</label>
                        <input type="text" v-model="appat_usu" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.appat_usu.$error, 'is-valid':!$v.appat_usu.$invalid }">
                        <div class="invalid-feedback">
                            <span v-if="!$v.appat_usu.required && !$v.appat_usu.letrasSpanishVacio">Solo letras o Vacio</span>
                            <span v-else-if="!$v.appat_usu.letrasSpanishVacio">Solo letras</span>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="text-input">Ap. Materno</label>
                        <input type="text" v-model="apmat_usu" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.apmat_usu.$error, 'is-valid':!$v.apmat_usu.$invalid }">
                        <div class="invalid-feedback">
                            <span v-if="!$v.apmat_usu.required && !$v.apmat_usu.letrasSpanishVacio">Solo letras o Vacio</span>
                            <span v-else-if="!$v.apmat_usu.letrasSpanishVacio">Solo letras</span>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="text-input">Email</label>
                        <input type="text" v-model="email" class="form-control" :class="{ 'is-invalid' : $v.email.$error, 'is-valid':!$v.email.$invalid }">
                        <div class="invalid-feedback">
                            <span v-if="!$v.email.required">Este campo es Requerido</span>
                            <span v-else-if="!$v.email.email">Email Incorrecto</span>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="">Roles</label>
                        <select class="form-control" v-model="rol" :class="{ 'is-invalid' : $v.rol.$error, 'is-valid':!$v.rol.$invalid }">
                            <option value="" disabled>SELECCIONE</option>
                            <option v-for="rol in Aroles" :key="rol.id" :value="rol.id"  v-text="rol.name"></option>
                        </select>
                        <div class="invalid-feedback">
                            <span v-if="!$v.rol.required">Este campo es Requerido</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="CrearUsuario()">REGISTRAR</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar(1)">CERRAR</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



        <div class="modal fade" data-backdrop="static" id="NuevoUsuario">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">{{datos.grado}} {{datos.complemento}} {{datos.nombre}} {{datos.paterno}} {{datos.materno}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <!-- llenado de informacion de Datos -->
                        <div class="row">
                            <!-- Foto del Personal -->
                            <div  class="col-md-2" style="vertical-align:middle;">
                                 <img :src="'https://sipefab.fab.bo/img/personal/'+datos.foto" width="100%" height="100%">
                            </div>
                            <!-- datos personales -->
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">CARNET MILITAR</dt>
                                            <dd class="st">{{ datos.cm }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">CARNET DE IDENTIDAD</dt>
                                            <dd class="st">{{ datos.ci }} {{ datos.expedido }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">SITUACION</dt>
                                            <dd class="st">{{ datos.situacion }}</dd>
                                        </dl>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <dl>
                                            <dt class="st">DESTINO ACTUAL</dt>
                                            <dd class="st">{{ datos.des2 }} {{ datos.des3 }}</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center"> 
                                    <div class="col-md-4">
                                        <label for="">NICK:</label>
                                        <input type="text" style=" background-color: rgba(182, 171, 171, 0.849); text-align: center;" class="form-control" disabled v-model="nick">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">PERMISOS:</label>
                                        <v-select
                                            label="name"
                                            :options="Aroles"
                                            v-model="rol"
                                        >
                                            //EN CASO QUE NO SE ENCENTRE EL VALOR EN LA LISTA
                                            <template v-slot:no-options="{ search, searching }">
                                            <template v-if="searching">
                                                Lo sentimos, no hay opciones de coincidencia.<em>{{
                                                search
                                                }}</em
                                                >.
                                            </template>
                                            <em v-else
                                                >Lo sentimos, no hay opciones de coincidencia.</em
                                            >
                                            </template>
                                        </v-select>

                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md-12" for="">SECCION:</label>
                                        <select class="form-control col-md-12" v-model="seccion">
                                            <option value="1">OFICIALES</option>
                                            <option value="2">SUBOFICIALES Y SARGENTOS</option>
                                            <option value="3">PERSONAL CIVIL</option>
                                            <option value="4">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="CrearUsuario()">Crear Usuario</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- Modal Editar Usuario -->
        <div class="modal fade" data-backdrop="static" id="EditarUsuario">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">{{Edatos.nombres}} {{Edatos.ap_paterno}} {{Edatos.ap_materno}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar(2)">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <!-- llenado de informacion de Datos -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">Nonmbre</dt>
                                            <dd class="st">
                                                <input type="text" v-model.trim="nom_usuA" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.nom_usuA.$error, 'is-valid':!$v.nom_usuA.$invalid }">
                                                <div class="invalid-feedback">
                                                    <span v-if="!$v.nom_usuA.required">Este campo es Requerido</span>
                                                    <span v-else-if="!$v.nom_usuA.letrasSpanish">Solo letras</span>
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">Ap. Paterno</dt>
                                            <dd class="st">
                                                <input type="text" v-model.trim="appat_usuA" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.appat_usuA.$error, 'is-valid':!$v.appat_usuA.$invalid }">
                                                <div class="invalid-feedback">
                                                    <span v-if="!$v.appat_usuA.required && !$v.appat_usuA.letrasSpanish">Solo letras o Vacio</span>
                                                    <span v-else-if="!$v.appat_usuA.letrasSpanish">Solo letras</span>
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt class="st">Ap. Materno</dt>
                                            <dd class="st">
                                                <input type="text" v-model.trim="apmat_usuA" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.apmat_usuA.$error, 'is-valid':!$v.apmat_usuA.$invalid }">
                                                <div class="invalid-feedback">
                                                    <span v-if="!$v.apmat_usuA.required && !$v.apmat_usuA.letrasSpanish">Solo letras o Vacio</span>
                                                    <span v-else-if="!$v.apmat_usuA.letrasSpanish">Solo letras</span>
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <dl>
                                            <dt class="st">Email</dt>
                                            <dd class="st"> 
                                                <input type="text" v-model.trim="emailA" class="form-control" :class="{ 'is-invalid' : $v.emailA.$error, 'is-valid':!$v.emailA.$invalid }">
                                                <div class="invalid-feedback">
                                                    <span v-if="!$v.emailA.required">Este campo es Requerido</span>
                                                    <span v-else-if="!$v.emailA.email">Email Incorrecto</span>
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center"> 
                                    <div class="col-md-4">
                                        <label for="">Nick</label>
                                        <input type="text" style=" background-color: rgba(182, 171, 171, 0.849); text-align: center;" class="form-control" disabled v-model="Edatos.nick">
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="EditarUsuario()">EDITAR</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar(2)">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->




        <!-- AGREGAR ROLES A UN USUARIO -->
        <div class="modal fade" data-backdrop="static" id="AgregarRoles">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ASIGNAR ROLES</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar(3)">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">                    
                        <div class="col-md-12">
                            <label>Roles</label>
                            <v-select
                                label="name"
                                :options="Aroles"
                                v-model="role"
                                :class="{
                                    'is-invalid': $v.role.$error,
                                    'is-valid': !$v.role.$invalid
                                }"
                            >
                                <!-- EN CASO QUE NO SE ENCUENTRE EL VALOR EN LA LISTA -->
                                <template v-slot:no-options="{ search, searching }">
                                    <template v-if="searching">
                                        Lo sentimos, no hay opciones de coincidencia para
                                        <em>{{ search }}</em>.
                                    </template>

                                    <em v-else>
                                        Lo sentimos, no hay opciones disponibles.
                                    </em>
                                </template>
                            </v-select>

                            <div class="invalid-feedback">
                                <span v-if="!$v.role.required">
                                    Este campo es requerido
                                </span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" @click="AgregarRol()">ASIGNAR ROL</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Roles Asignados</label>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 25%" class="text-center">OPCIONES</th>
                                    <th style="width: 75%" class="text-center">ROL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in Usrol">
                                    <td class="text-center">
                                        <button  type="button" class="btn btn-danger btn-sm" @click="EliminarRol(u.name)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">{{u.name}} </td>
                                    
                                </tr>
                            </tbody>
                            
                        </table> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar(3)">CERRAR</button>
                </div>

            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
import { required, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";
export default {
    data() {
        return {
            /**
             * Variables
             */
            // VARIABLES NEW USER
            nom_usu : '',
            appat_usu : '',
            apmat_usu : '',
            email : '',
            // FIN VARIBLES NEW USER
            // VARIABLES MODIFICAR USER
            nom_usuA : '',
            appat_usuA : '',
            apmat_usuA : '',
            emailA : '',
            // FIN VARIBLES MODIFICAR USER

            iduser: '',
            role:[],



            per: [],
            nick: '',
            password: null,
            Epassword: '',
            Eid: '',
            rol: '',
            idrol: '',
            /**
             * Array de personal
             */
            Apersonal: [],
            Ausuarios: [],
            Aroles: [],
            datos: [],
            Edatos: [],
            Erole: [],
            Erol: [],
            idErol:'',
            Aroles:[],
            
            Usrol:[],
            
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
            seccion: 1,
            eseccion: '',
        }
    },

    validations: {
        nom_usu : { required, letrasSpanish: value => /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        appat_usu : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        apmat_usu : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        email : { required, email},
        rol : { required },

        nom_usuA : { required, letrasSpanish: value => /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        appat_usuA : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        apmat_usuA : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        emailA : { required, email},

        role : { required },

        validationsGroupReg: [
            'nom_usu',
            'appat_usu',
            'apmat_usu',
            'email',
            'rol'
        ],

        validationsGroupMod: [
            'nom_usuA',
            'appat_usuA',
            'apmat_usuA',
            'emailA',
            // 'rol'
        ],

        validationsGroupRol: [
            'role'
        ],
    },

    mounted() {
        // this.ListarPersonal();
        this.ListarUsuarios(1);
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
        NuevoUsuario(){ //BASE
            this.$v.validationsGroupReg.$reset(),
            //PONER DE CERO EL MODAL ANTES DE REGISTRAR
            this.nom_usu = '',
            this.appat_usu = '',
            this.apmat_usu = '',
            this.email = '',
            this.rol = '',
            //FIN PONER A CERO MODAL
            $('#ModalUsuario').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
            $(".modal-title-registro").text("NUEVO USUARIO");
            this.ListarRoles();
        },

        ListarRoles(){ //BASE
            let me = this;
            axios
            .get('/listarRoles')
            .then(function (response) {
                
                me.Aroles = response.data;
                me.Erole = response.data;
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            });
        },

        NuevoUsuarioModal(){
            try {
                if (this.per.id) {
                    let me = this;
                    me.ListarRoles();
                    axios
                    .post("/datPer", {
                        percodigo: me.per.id
                    })
                    .then(function (response) {
                        
                        me.datos = response.data;
                        me.nick = me.datos.nombre.substring(0,1)+ me.datos.paterno.substring(0,1)+ me.datos.materno.substring(0,1)+me.datos.percodigo+me.datos.cm.substring(0,3);
                        $('#NuevoUsuario').modal('show');
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                } else {
                    swal.fire(
                        "Error", //TITULO
                        "Debe seleccionar a un personal de la institucion.", //TEXTO DE MENSAJE
                        "error" // TIPO DE MODAL (success, warnning, error, info)
                    );
                }
                
            } catch (error) {
                swal.fire(
                    "Error", //TITULO
                    "Debe seleccionar a un personal de la institucion.", //TEXTO DE MENSAJE                    
                    "error" // TIPO DE MODAL (success, warnning, error, info)
                );
                
            }
            
            
        },

        CrearUsuario(){ //BASE
            if(!this.$v.validationsGroupReg.$invalid){
                swal.fire({
                    title: '¿Desea registrar este usuario?', // TITULO 
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
                        axios
                        .post("/crearUsuario", { 
                            nombres : me.nom_usu,
                            ap_paterno : me.appat_usu,
                            ap_materno : me.apmat_usu,
                            email : me.email,
                            rol: me.rol,
                        })
                        .then(function (response) {
                        
                        console.log(response);
                            swal.fire(
                                "REGISTRADO", //TITULO
                                "Se registro correctamente el usuario.", //TEXTO DE MENSAJE
                                "success" // TIPO DE MODAL (success, warnning, error, info)
                            );
                            // $('#NuevoUsuario').modal('hide');
                            $('#ModalUsuario').modal('hide');
                            me.Cerrar(1);
                            me.ListarUsuarios(1);
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                    }else{
                        let me = this;
                        swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        $('#ModalUsuario').modal('hide');
                        me.Cerrar(1);
                        me.ListarUsuarios(1);
                    }
                })
            }else{
                this.$v.validationsGroupReg.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                }) 
            }
        },

        ModalRoles(usuario_id,user_id){
            this.$v.validationsGroupRol.$reset(),
            this.RolesNoUsuario(user_id);
            this.RolesUsuario(user_id);
            this.iduser = user_id;
            $('#AgregarRoles').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },

        RolesNoUsuario(user_id){
            let me = this;
            axios
            .post("/listarol2", {
                user_id: user_id
            })
            .then(function (response) {
                //Respuesta de la peticion
                console.log(response);
                me.Aroles = response.data;                

            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        },

        RolesUsuario(user_id){
            let me = this;
            
            axios
            .post("/listarolus", {
                user_id: user_id
            })
            .then(function (response) {
                //Respuesta de la peticion
                console.log(response);
                me.Usrol = response.data;

            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        },

        EliminarRol(name){
            swal.fire({
                title: '¿Desea eliminar este rol al usuario?', // TITULO 
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
                    axios
                    .post("/quitarRol", {
                        user_id: me.iduser,
                        rol: name
                    })
                    .then(function (response) {
                    console.log(response);
                        swal.fire(
                            "ELIMINADO", //TITULO
                            "Se elimino correctamente el rol al usuario.", //TEXTO DE MENSAJE
                            "success" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        // $('#NuevoUsuario').modal('hide');
                        $('#AgregarRoles').modal('hide');
                        me.Cerrar(3);
                        me.ListarUsuarios(1);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                }else{
                    let me = this;
                    swal.fire(
                        "Informacion", //TITULO
                        "Solicitud cancelada.", //TEXTO DE MENSAJE
                        "info" // TIPO DE MODAL (success, warnning, error, info)
                    );
                    $('#AgregarRoles').modal('hide');
                    me.Cerrar(3);
                    me.ListarUsuarios(1);
                }
            })
        },

        AgregarRol(){
            if(!this.$v.validationsGroupRol.$invalid){
                swal.fire({
                    title: '¿Desea agregar este rol al usuario?', // TITULO 
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
                        axios
                        .post("/agregarRol", {
                            user_id: me.iduser,
                            rol: me.role.name
                        })
                        .then(function (response) {
                        console.log(response);
                            swal.fire(
                                "AGREGADO", //TITULO
                                "Se agrego correctamente el rol al usuario.", //TEXTO DE MENSAJE
                                "success" // TIPO DE MODAL (success, warnning, error, info)
                            );
                            // $('#NuevoUsuario').modal('hide');
                            $('#AgregarRoles').modal('hide');
                            me.Cerrar(3);
                            me.ListarUsuarios(1);
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                    }else{
                        let me = this;
                        swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        $('#AgregarRoles').modal('hide');
                        me.Cerrar(3);
                        me.ListarUsuarios(1);
                    }
                })
            }else{
                this.$v.validationsGroupRol.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                }) 
            }
        },

        cambiarPagina(page){
            let me = this;
            me.pagination.current_page = page;
            me.ListarUsuarios(page);
        },

        ListarUsuarios(page){
            let me = this;
            axios
            .post("/listarUsuarios", {
                buscar: me.buscar.toUpperCase(),
                page: page
            })
            .then(function (response) {
                
                me.Ausuarios = response.data.usuarios.data;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            
        },

        BuscarUsuario(){
            clearTimeout(this.setTiemoutBuscador);
            this.setTiemoutBuscador = setTimeout(() => {
                this.ListarUsuarios(1)
            }, 360)
        },

        EditarModal(usuario_id,user_id){
            let me = this;
            this.$v.validationsGroupMod.$reset(),
            me.Eid = usuario_id;
            me.ListarRoles();
            axios
            .post("/datosUsuarios", {
                user_id: user_id
            })
            .then(function (response) {
                console.log(response);
                me.Edatos = response.data.usuarios;
                me.eseccion = response.data.usuarios.seccion;
                me.Erol = response.data.role.name
                me.namErol = response.data.role.name;
                me.nom_usuA = me.Edatos.nombres;
                me.appat_usuA = me.Edatos.ap_paterno;
                me.apmat_usuA = me.Edatos.ap_materno;
                me.emailA = me.Edatos.email;
                $('#EditarUsuario').modal('show');
                $(".modal-header").css("background-color", "#007bff");
                $(".modal-header").css("color", "white" );
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        },

        EditarUsuario(){
            if(!this.$v.validationsGroupMod.$invalid){
            // if (this.Erol.name) {
            //     var role = this.Erol.name;
            // } else {
            //     var role = this.namErol;
            // }
                swal.fire({
                    title: '¿Desea modificar este usuario?', // TITULO 
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
                        axios
                        .put("/editarUsuarios", {
                            usuario_id: me.Eid,
                            // seccion: me.eseccion
                            nombre: me.nom_usuA,
                            ap_paterno: me.appat_usuA,
                            ap_materno: me.apmat_usuA,
                            email: me.emailA,
                        })
                        .then(function (response) {
                            swal.fire(
                                "MODIFICADO", //TITULO
                                "Se modifico correctamente el usuario.", //TEXTO DE MENSAJE
                                "success" // TIPO DE MODAL (success, warnning, error, info)
                            );
                            $('#EditarUsuario').modal('hide');
                            me.Cerrar(2);
                            me.ListarUsuarios(1);
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })                    
                    }else{
                        let me = this;
                        swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        $('#EditarUsuario').modal('hide');
                        me.Cerrar(2);
                        me.ListarUsuarios(1);
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
        CambioEstado(estado, usuario_id, user_id){
            if (estado == 1) {
                var titulo = '¿Desea deshabilitar este usuario?';
                var titulo2 = 'Usuario deshabilitado correctamente';
            } else {
                var titulo = '¿Desea habilitar este usuario?';
                var titulo2 = 'Usuario habilitado correctamente';
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
                    axios
                    .put("/cambiarEstadoUsuario", {
                        user_id: user_id,
                        estado: estado,
                    })
                    .then(function (response) {
                        
                        console.log(response);
                        me.ListarUsuarios(1);
                        swal.fire(
                            "Informacion", //TITULO
                            titulo2, //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                        
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })                    
                }else{
                     swal.fire(
                        "Informacion", //TITULO
                        "Solicitud cancelada.", //TEXTO DE MENSAJE
                        "info" // TIPO DE MODAL (success, warnning, error, info)
                    );
                }
            })
        },

        Cerrar(valor){
            switch (valor) {
                case 1:
                    this.nom_usu = '',
                    this.appat_usu = '',
                    this.apmat_usu = '',
                    this.email = ''
                    break;
                case 2:
                    this.nom_usuA = '',
                    this.appat_usuA = '',
                    this.apmat_usuA = '',
                    this.emailA = ''
                    break;
                case 3:
                    this.iduser = '',
                    this.role = []
                    break;
                default:
                    break;
            }
        },
    },
};
</script>

<style>
.stlabel {
  font-size: 20px;
}

@media (min-width: 992px) {
  .modal-lg {
    max-width: 40%;
  }
  .modal-lg2 {
    max-width: 50%;
  }
}
</style>