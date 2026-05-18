<template>
<div class="container">

    <div class="row mb-4">

        <!-- Año -->
        <div class="col-md-3">
            <label>Año</label>

            <select class="form-control"
                    v-model="anio"
                    @change="generarCalendario">

                <option v-for="a in anios"
                        :key="a"
                        :value="a">

                    {{ a }}

                </option>

            </select>
        </div>

        <!-- Mes -->
        <div class="col-md-3">
            <label>Mes</label>

            <select class="form-control"
                    v-model="mes"
                    @change="generarCalendario">

                <option v-for="(m, index) in meses"
                        :key="index"
                        :value="index">

                    {{ m }}

                </option>

            </select>
        </div>

    </div>

    <!-- Calendario -->
    <div class="calendar">

        <!-- Dias -->
        <div class="calendar-header"
             v-for="dia in diasSemana"
             :key="dia">

            {{ dia }}

        </div>

        <!-- Celdas -->
        <div v-for="(dia, index) in calendario"
             :key="index"
             class="calendar-day"
             :class="{ empty: !dia }"
             @click="dia && seleccionarDia(dia)">

            <div v-if="dia">

                <strong>{{ dia }}</strong>

            </div>

        </div>

    </div>






    <div class="modal fade"  data-backdrop="static" id="ModalEvento">
      <div class="modal-dialog modal-md ">
        <div class="modal-content">
          <div class="modal-header">
              <template v-if="modal == 0">
                  <h4 class="modal-title">NUEVO EVENTO</h4>
              </template>
              <template v-else>
                  <h4 class="modal-title">EDITAR PERMISO</h4>
              </template>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <label class="form-control-label" for="text-input">FECHA EVENTO:</label>
                      <input type="text" class="form-control" v-model="fecha_evento" :class="{ 'is-invalid' : $v.fecha_evento.$error, 'is-valid':!$v.fecha_evento.$invalid }" disabled>
                      <div class="invalid-feedback">
                          <span v-if="!$v.fecha_evento.required">Este campo es Requerido</span>
                      </div>
                  </div>

                  <div class="col-md-12">
                      <label class="form-control-label" for="text-input">RESPONSABLE:</label>
                      <input type="text" class="form-control" v-model="responsable" :class="{ 'is-invalid' : $v.responsable.$error, 'is-valid':!$v.responsable.$invalid }">
                      <div class="invalid-feedback">
                          <span v-if="!$v.responsable.required">Este campo es Requerido</span>
                      </div>
                  </div>

                  <div class="col-md-12">
                      <label for="">DETALLE:</label>
                      <textarea name="" class="form-control" v-model="tipo_evento" style="text-transform:uppercase;" cols="30" rows="2" :class="{ 'is-invalid' : $v.tipo_evento.$error, 'is-valid':!$v.tipo_evento.$invalid }"></textarea>
                      <div class="invalid-feedback">
                          <span v-if="!$v.tipo_evento.required">Este campo es Requerido</span>
                      </div>
                  </div>
                  <!-- <div class="col-md-12">
                      <label>MODULO:</label>
                      <v-select
                          label="nombre"
                          :options="Amodulos"
                          v-model="modulo"
                          :class="{
                              'is-invalid': $v.modulo.$error,
                              'is-valid': !$v.modulo.$invalid
                          }"
                      >
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
                          <span v-if="!$v.modulo.required">
                              Este campo es requerido
                          </span>
                      </div>
                  </div> -->
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









</div>
</template>

<script>
import { required, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";
export default {

    data() {

        return {

            anio: new Date().getFullYear(),

            mes: new Date().getMonth(),

            anios: [],

            meses: [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre'
            ],

            diasSemana: [
                'Lun',
                'Mar',
                'Mié',
                'Jue',
                'Vie',
                'Sáb',
                'Dom'
            ],

            calendario: [],

            modal: 0,
            responsable: '',
            tipo_evento: '',
            fecha_evento: '',
        }

    },

    validations: { 
        responsable: { required },
        tipo_evento: { required },
        fecha_evento: { required },

        validationsGroupReg: [
            'responsable',
            'tipo_evento',
            'fecha_evento',
        ],

        validationsGroupMod: [
            // 'nombre',
            // 'detalle',
            // 'modulo',
        ],
    },

    mounted() {

        let actual = new Date().getFullYear();

        for (let i = actual - 5; i <= actual + 5; i++) {
            this.anios.push(i);
        }

        this.generarCalendario();

    },

    methods: {

        generarCalendario() {

            this.calendario = [];

            let primerDia = new Date(this.anio, this.mes, 1);

            let ultimoDia = new Date(this.anio, this.mes + 1, 0);

            let inicioSemana = primerDia.getDay();

            // Ajustar domingo
            inicioSemana = inicioSemana === 0 ? 6 : inicioSemana - 1;

            // Espacios vacíos
            for (let i = 0; i < inicioSemana; i++) {
                this.calendario.push(null);
            }

            // Dias del mes
            for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
                this.calendario.push(dia);
            }

        },

        seleccionarDia(dia) {
            let fecha =
                this.anio + '-' +
                String(this.mes + 1).padStart(2, '0') + '-' +
                String(dia).padStart(2, '0');

            this.fecha_evento = fecha;

            // alert('Registrar evento en: ' + fecha);

            this.$v.validationsGroupReg.$reset(),
            this.modal = 0;
            // this.ListarModulos();
            $('#ModalEvento').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white" );
        },

        Cerrar(){
            this.responsable = '',
            this.tipo_evento = '',
            this.fecha_evento = ''
        },

        Guardar() {
          if(!this.$v.validationsGroupReg.$invalid){
              swal.fire({
                  title: '¿Desea registrar este evento?', // TITULO 
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
                      .post("/registrarEvento", {
                          responsable: me.responsable.toUpperCase(),
                          tipo_evento: me.tipo_evento.toUpperCase(),
                          fecha_evento: me.fecha_evento
                      })
                      .then(function (response) {
                          swal.fire(
                              "REGISTRADO", //TITULO
                              "Se registro correctamente el evento.", //TEXTO DE MENSAJE
                              "success" // TIPO DE MODAL (success, warnning, error, info)
                          );
                          $('#ModalEvento').modal('hide');
                          me.Cerrar();
                          // me.ListarPermisos(1);
                          
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
                      $('#ModalEvento').modal('hide');
                      me.Cerrar();
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
        }

    }

}
</script>

<style scoped>

.calendar {

    display: grid;

    grid-template-columns: repeat(7, 1fr);

    gap: 5px;

}

.calendar-header {

    background: #343a40;

    color: white;

    text-align: center;

    padding: 10px;

    font-weight: bold;

}

.calendar-day {

    min-height: 100px;

    border: 1px solid #ccc;

    padding: 10px;

    cursor: pointer;

    transition: 0.2s;

    background: white;

}

.calendar-day:hover {

    background: #f0f0f0;

}

.empty {

    background: #f8f9fa;

    cursor: default;

}

</style>