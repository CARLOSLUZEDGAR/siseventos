<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                REGISTRAR
                <!-- <small>Personal</small> -->
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <!-- <li class="breadcrumb-item"><a href="/">Inicio</a></li> -->
                <!-- <li class="breadcrumb-item active">Datos del Personal</li> -->
              </ol>
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
                  <div class="row">
                    <div class="col-sm-8">
                      <h3 class="card-title">
                        <!-- <i class="fas fa-search"></i> -->
                        <!-- Buscar Personal -->
                      </h3>  
                    </div>                  
                    <div class="col-sm-4">
                      <button class="btn btn-danger btn-sm float-right" type="submit" @click="Atras()">
                        <i class="fas fa-arrow-left"></i>&nbsp; VOLVER
                      </button>
                    </div>
                  </div>  
                </div>
                <div class="card-body">
                  <form>
                        <h3><i class="fas fa-inbox"></i>&nbsp;REGISTRAR INFORMACIÓN</h3>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Carnet de Identidad (Identificación Personal)</label>
                                <input type="text" v-model="per_ci" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_ci.$error, 'is-valid':!$v.per_ci.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_ci.required">Este campo es Requerido</span>
                                </div>
                            </div>               
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Nombres</label>
                                <input type="text" v-model.trim="per_nombre" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_nombre.$error, 'is-valid':!$v.per_nombre.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_nombre.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_nombre.letrasSpanish">Solo letras</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Ap. Paterno</label>
                                <input type="text" v-model.trim="per_appaterno" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_appaterno.$error, 'is-valid':!$v.per_appaterno.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_appaterno.required && !$v.per_appaterno.letrasSpanishVacio">Solo letras o Vacio</span>
                                    <span v-else-if="!$v.per_appaterno.letrasSpanishVacio">Solo letras</span>
                                </div>  
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Ap. Materno</label>
                                <input type="text" v-model.trim="per_apmaterno" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_apmaterno.$error, 'is-valid':!$v.per_apmaterno.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_apmaterno.required && !$v.per_apmaterno.letrasSpanishVacio">Solo letras o Vacio</span>
                                    <span v-else-if="!$v.per_apmaterno.letrasSpanishVacio">Solo letras</span>
                                </div>                                
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Fecha Nacimiento</label>
                                <input type="date" v-model="per_fechnac" class="form-control" :class="{ 'is-invalid' : $v.per_fechnac.$error, 'is-valid':!$v.per_fechnac.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_fechnac.required">Este campo es Requerido</span>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Sexo</label>
                                <select class="form-control" v-model="per_sexo" :class="{ 'is-invalid' : $v.per_sexo.$error, 'is-valid':!$v.per_sexo.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FEMENINO">FEMENINO</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_sexo.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Celular </label>
                                <input type="text" v-model.number="per_celular" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_celular.$error, 'is-valid':!$v.per_celular.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_celular.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_celular.numeric">Solo digitos</span>
                                    <span v-else-if="!$v.per_celular.length">Debe contener 8 digitos</span>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">E-mail </label>
                                <input type="text" v-model.trim="per_email" class="form-control" :class="{ 'is-invalid' : $v.per_email.$error, 'is-valid':!$v.per_email.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_email.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_email.email">Email Incorrecto</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label" for="text-input">Dirección</label>
                                <input type="text" v-model.trim="per_direccion" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_direccion.$error, 'is-valid':!$v.per_direccion.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_direccion.required">Este campo es Requerido</span>
                                </div>
                            </div>                             
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3" style="text-align: center;">
                              <template>
                                <!-- <img :src="v === 0 ? '/img/avatar.jpg' : imagen" width="150" height="150" style="border: 1.5px solid black;"> -->
                                <img :src="v === 0 ? 'URL_AVATAR_SUPABASE' : imagen" width="150" height="150" style="border: 1.5px solid black;">
                              </template> 
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Fotografia</label>
                                <input type="file" class="form-control" @change="obtenerImagen" accept="image/*" v-bind:class="{ 'is-invalid': $v.per_foto.$error, 'is-valid':!$v.per_foto.$invalid }">
                                <div class="invalid-feedback">
                                    <div v-if="!$v.per_foto.required">Por favor, carga tu fotografia.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                          <button type="button" class="btn btn-danger" @click="CrearPersonal()"><i class="fas fa-address-card"></i>&nbsp; REGISTRAR</button>
                        </div>
                                 
                  </form>
                </div>
                <!-- /.card -->
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- ./row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
  </template>
  
  <script>
  import { required, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";
  export default {
    data() {
      return {
        // INICIO VARIABLES DGAE
        per_foto : '',
        per_ci : '',
        per_nombre : '',
        per_appaterno : '',
        per_apmaterno : '',
        per_sexo : '',
        per_celular : '',
        per_email : '',
        per_fechnac : '',
        per_direccion : '',
        v : 0,
        arraySexo : ['MASCULINO','FEMENINO'],
        // FIN VARIABLES DGAE
        arrayPersonal : [],
        
        listaPersonal: [],
        criterio: "p.per_cm",
        buscar:"",
        setTiemoutBuscador: '',
        isHidden: true,
        datfis2 : 1,
  
          
        pagination: {
              'total'         :   0,
              'current_page'  :   0,
              'per_page'      :   0,
              'last_page'     :   0,
              'from'          :   0,
              'to'            :   0,
          },
      offset : 3,
      }
    },
  
    validations: {
      per_foto: { required },
      per_ci: { required },
      per_nombre : { required, letrasSpanish: value => /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
      per_appaterno : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
      per_apmaterno : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },            
      per_sexo : { required },
      per_celular : { required, numeric, hasSpecificLength: value => value && value.toString().length === 8 },
      per_email : { required, email},
      per_fechnac : { required },
      per_direccion : { required },

      validationGroupReg: [
      'per_foto',
      'per_ci',
      'per_nombre',
      'per_appaterno',
      'per_apmaterno',
      'per_sexo',
      'per_celular',
      'per_email',
      'per_fechnac',
      'per_direccion'],
    },
  
   computed: {
          isActived   : function(){
              return this.pagination.current_page;
          },
          //creando funcion para calcular los elementos de la paginacion
          pagesNumber : function(){
              //si la pagina llega hasta la ultima pagina esta me va retornar un arrary vacio
              if(!this.pagination.to){
                  return [];
              }
  
              var from =  this.pagination.current_page - this.offset;
              if(from < 1){
                  from = 1;
              }
  
              var to = from + (this.offset * 2);
              if(to >= this.pagination.last_page){
                  to = this.pagination.last_page;
              }
  
              var pagesArray = [];
              while(from <= to){
                  pagesArray.push(from);
                  from++;
              }
              return pagesArray;
          },
          imagen(){
              return this.per_foto;
          },
          imagenA(){
              return this.per_fotoA;
          }
      },
    mounted() {
        this.NuevoPersonal();
    },

    methods: {

      Atras(){ //DGAE
          this.$router.push({
              name: "DatosPersonal",  
          });
      },

      obtenerImagen(e){
          try {
              var fileReader = new FileReader();
  
              fileReader.onload = (e) => {
                  this.per_foto = e.target.result;
              }
              fileReader.readAsDataURL(e.target.files[0])
              this.v = 1;
          } catch (error) {
              
          }
      },

      NuevoPersonal(){ //DGAE
        this.$v.validationGroupReg.$reset(),
        this.per_foto = '',
        this.per_ci = '',
        this.per_nombre = '',
        this.per_appaterno = '',
        this.per_apmaterno = '',
        this.per_sexo = '',
        this.per_celular = '',
        this.per_email = '',
        this.per_fechnac = '',
        this.per_direccion = '',
        this.v = 0
      },
  
      CrearPersonal(){ //DGAE
        if(!this.$v.validationGroupReg.$invalid){
          swal.fire({
              title: '¿Desea registrar?', // TITULO 
              icon: 'question', //ICONO (success, warnning, error, info, question)
              showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
              confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
              cancelButtonColor: '#868077', // COLOR DEL BOTON CANCELAR
              confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
              cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
              buttonsStyling: true,
              reverseButtons: true
              }).then((result) => {
              if (result.value) {
                  let me = this;
                  axios
                  .post("/crearPersonal", {
                    foto : me.per_foto,
                    ci : me.per_ci,
                    nombre : me.per_nombre,
                    ap_paterno : me.per_appaterno,
                    ap_materno : me.per_apmaterno,
                    sexo : me.per_sexo,
                    celular : me.per_celular,
                    email : me.per_email,
                    fech_nac : me.per_fechnac,
                    direccion : me.per_direccion,
                  })
                  .then(function (response) {
                      
                      console.log(response);
                      swal.fire({
                          title: 'Se realizo el registro correctamente', //TITULO
                          // response.data.mensaje, //TEXTO DE MENSAJE
                          // response.data.tipo, // TIPO DE MODAL (success, warnning, error, info)
                          // response.personal
                      });
                      if (!response.data.code) {
                          // $('#NuevoUsuario').modal('hide');
                          // $('#ModalNewPersonal').modal('hide');
                          // me.nick = '';
                          // me.password = '';
                          me.arrayDatPer = response.data.personal;
                          // me.GenerarCarnet(me.arrayDatPer.id_personal);
                          me.Atras();
                          this.$v.$reset();
                      } 
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
        }else{
            this.$v.validationGroupReg.$touch();
            Swal.fire({
                icon: 'warning',
                title: 'Ingrese todos los datos requeridos',
                showConfirmButton: false,
                timer: 2000
            })
            
        }
      },

      GenerarCarnet(id_personal){ //DGAE
          // this.$v.$reset();
          // if(!this.$v.$invalid){
          window.open('/carnet?id_p='+id_personal);
          // window.open('http://127.0.0.1:8000/carnet?id_p='+id_personal);
          // }else{
          //     this.$v.$touch();
          //     Swal.fire({
          //         icon: 'warning',
          //         title: 'Ingrese todos los datos requeridos',
          //         showConfirmButton: false,
          //         timer: 2000
          //     })
              
          // }
      },
  
    },
  };
  
  </script>
  
  <style>
  </style>