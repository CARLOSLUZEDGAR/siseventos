<template>
    <div class="container">

        <div class="row mb-4">

            <!-- Año -->
            <div class="col-md-3">
                <label>GESTIÓN</label>

                <select class="form-control"
                        v-model="anio"
                        @change="actualizarCalendario">

                    <option v-for="a in anios"
                            :key="a"
                            :value="a">

                        {{ a }}

                    </option>

                </select>
            </div>

            <!-- Mes -->
            <div class="col-md-3">
                <label>MES</label>

                <select class="form-control"
                        v-model="mes"
                        @change="actualizarCalendario">

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

                    <!-- NUMERO DIA -->
                    <strong>{{ dia }}</strong>

                    <!-- EVENTOS -->
                    <div
                        v-for="(evento, index) in obtenerEventosDia(dia)"
                        :key="index"
                        class="evento-calendario"
                        :style="{
                            backgroundColor: evento.color
                        }"
                    >
                        {{ evento.nombre }}
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEvento">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header">
                        <!-- <template v-if="modal == 0"> -->
                            <h4 class="modal-title">
                                FECHA: {{ formatearFecha }}
                            </h4>
                        <!-- </template>
                        <template v-else>
                            <h4 class="modal-title">
                                EDITAR EVENTO
                            </h4>
                        </template> -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar(1)">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <!-- EVENTOS REGISTRADOS -->
                        <div v-if="eventosDiaSeleccionado.length > 0">
                            <h5 class="mb-3">
                                SALONES OCUPADOS
                            </h5>

                            <div class="card mb-3" v-for="(evento, index) in eventosDiaSeleccionado" :key="index">
                                <div class="card-header text-white" :style="{ backgroundColor: evento.color }">
                                    <strong>
                                        {{ evento.nombre }}
                                    </strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>RESPONSABLE:</strong>
                                            {{ evento.contratante }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>TIPO EVENTO:</strong>
                                            {{ evento.evento }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>TARIFA:</strong>
                                            {{ evento.tarifa }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>FECHA:</strong>
                                            {{ evento.fecha_evento }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="button" class="btn btn-primary" @click="editarEventoDia(evento.id)">EDITAR</button>
                                </div>
                            </div>
                        </div>
                        <!-- FORMULARIO -->
                        <div v-if="prediosDisponibles.length > 0">
                            <hr>
                            <h5 class="mb-3">
                                REGISTRAR NUEVO EVENTO
                            </h5>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                FECHA EVENTO:
                                            </label>
                                            <input type="text" class="form-control" :value="formatearFecha" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label>PREDIO:</label>
                                            <v-select
                                                label="nombre"
                                                :options="prediosDisponibles"
                                                v-model="predio"
                                                :class="{
                                                    'is-invalid': $v.predio.$error,
                                                    'is-valid': !$v.predio.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.predio.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label class="form-control-label">
                                                RESPONSABLE:
                                            </label>
                                            <input type="text" class="form-control" v-model="responsable" style="text-transform:uppercase;" :class="{'is-invalid' : $v.responsable.$error, 'is-valid': !$v.responsable.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.responsable.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>TIPO EVENTO:</label>
                                            <v-select
                                                label="evento"
                                                :options="arrayTipoEvento"
                                                v-model="tipo_evento"
                                                :class="{
                                                    'is-invalid': $v.tipo_evento.$error,
                                                    'is-valid': !$v.tipo_evento.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tipo_evento.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>TARIFA:</label>
                                            <v-select
                                                label="tarifa"
                                                :options="arrayTarifa"
                                                v-model="tarifa"
                                                :class="{
                                                    'is-invalid': $v.tarifa.$error,
                                                    'is-valid': !$v.tarifa.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.

                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tarifa.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>SITUACIÓN:</label>
                                            <v-select
                                                label="situacion"
                                                :options="arraySituacion"
                                                v-model="situacion"
                                                :class="{
                                                    'is-invalid': $v.situacion.$error,
                                                    'is-valid': !$v.situacion.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.situacion.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>FORMA DE PAGO:</label>
                                            <v-select
                                                :options="arrayFormaPago"
                                                v-model="forma_pago"
                                                :class="{
                                                    'is-invalid': $v.forma_pago.$error,
                                                    'is-valid': !$v.forma_pago.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.forma_pago.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <label>OBSERVACIÓN:</label>
                                            <textarea class="form-control" v-model="observacion" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row mt-2 d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" @click="Cerrar()">CANCELAR</button> -->
                                        <button type="button" class="btn btn-primary" @click="Guardar()">GUARDAR</button>
                                    </div>  
                                </div>
                            </div>
                            
                        </div>
                        <!-- TODOS OCUPADOS -->
                        <div v-else class="alert alert-danger mt-3">
                            TODOS LOS SALONES YA ESTÁN OCUPADOS
                            PARA ESTA FECHA.
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar(1)">CANCELAR</button>
                        <!-- <button v-if="modal == 0 && prediosDisponibles.length > 0" type="button" class="btn btn-primary" @click="Guardar()">GUARDAR</button>
                        <button v-else type="button" class="btn btn-primary" @click="Editar()">EDITAR</button> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEditar">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header">
                        <!-- <template v-if="modal == 0"> -->
                            <h4 class="modal-title">
                                EDITAR EVENTO
                            </h4>
                        <!-- </template>
                        <template v-else>
                            <h4 class="modal-title">
                                EDITAR EVENTO
                            </h4>
                        </template> -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="Cerrar(2)">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <!-- FORMULARIO -->
                            <hr>
                            <!-- <h5 class="mb-3">
                                EDITAR EVENTO
                            </h5> -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                FECHA EVENTO:
                                            </label>
                                            <input type="date" class="form-control" v-model="fecha_eventoE" :class="{'is-invalid' : $v.fecha_eventoE.$error, 'is-valid': !$v.fecha_eventoE.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.fecha_eventoE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>PREDIO:</label>
                                            <input type="text" class="form-control" v-model="predioE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.predioE.$error, 'is-valid': !$v.predioE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.predioE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label class="form-control-label">
                                                RESPONSABLE:
                                            </label>
                                            <input type="text" class="form-control" v-model="responsableE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.responsableE.$error, 'is-valid': !$v.responsableE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.responsableE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>TIPO EVENTO:</label>
                                            <input type="text" class="form-control" v-model="tipo_eventoE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.tipo_eventoE.$error, 'is-valid': !$v.tipo_eventoE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tipo_eventoE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>TARIFA:</label>
                                            <input type="text" class="form-control" v-model="tarifaE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.tarifaE.$error, 'is-valid': !$v.tarifaE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tarifaE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>SITUACIÓN:</label>
                                            <v-select
                                                label="situacion"
                                                :options="arraySituacion"
                                                v-model="situacion"
                                                :class="{
                                                    'is-invalid': $v.situacion.$error,
                                                    'is-valid': !$v.situacion.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.situacion.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>FORMA DE PAGO:</label>
                                            <v-select
                                                :options="arrayFormaPago"
                                                v-model="forma_pago"
                                                :class="{
                                                    'is-invalid': $v.forma_pago.$error,
                                                    'is-valid': !$v.forma_pago.$invalid
                                                }"
                                            >
                                                <template v-slot:no-options="{ search, searching }">
                                                    <template v-if="searching">
                                                        Lo sentimos, no hay opciones de coincidencia para
                                                        <em>{{ search }}</em>
                                                    </template>
                                                    <em v-else>
                                                        Lo sentimos, no hay opciones disponibles.
                                                    </em>
                                                </template>
                                            </v-select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.forma_pago.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <label>OBSERVACIÓN:</label>
                                            <textarea class="form-control" v-model="observacionE" style="text-transform:uppercase;" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row mt-2 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" @click="Cerrar(2)">CANCELAR</button>
                                        <button type="button" class="btn btn-primary" @click="Guardar()">GUARDAR</button>
                                    </div>  
                                </div>
                            </div>
                            
                        
                    </div>
                    <!-- FOOTER -->
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar()">CANCELAR</button>
                        <button v-if="modal == 0 && prediosDisponibles.length > 0" type="button" class="btn btn-primary" @click="Guardar()">GUARDAR</button>
                        <button v-else type="button" class="btn btn-primary" @click="Editar()">EDITAR</button>
                    </div> -->
                </div>
            </div>
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
                'ENERO',
                'FEBRERO',
                'MARZO',
                'ABRIL',
                'MAYO',
                'JUNIO',
                'JULIO',
                'AGOSTO',
                'SEPTIEMBRE',
                'OCTUBRE',
                'NOVIEMBRE',
                'DICIEMBRE'
            ],

            diasSemana: [
                'LUN',
                'MAR',
                'MIÉ',
                'JUE',
                'VIE',
                'SÁB',
                'DOM'
            ],

            calendario: [],

            // modal: 0,
            responsable: '',
            fecha_evento: '',
            arrayPredios: [],
            predio: '',
            arrayTipoEvento: [],
            tipo_evento: '',
            arrayTarifa: [],
            tarifa: '',
            arraySituacion: [],
            situacion: '',
            arrayFormaPago: ['TRANSFERENCIA', 'EFECTIVO'],
            forma_pago: '',
            observacion: '',
            arrayEvento: [],
            eventosDiaSeleccionado: [],
            prediosDisponibles: [],
            arrayMostrarEvento: [],

            fecha_eventoE: '',
            responsableE: '',
            predio_idE: '',
            predioE: '',
            tipo_evento_idE: '',
            tipo_eventoE: '',
            tarifa_idE: '',
            tarifaE: '',
            observacionE: '',
            prediosDisponiblesEditar: [],
        }
    },

    validations: { 
        responsable: { required },
        fecha_evento: { required },
        predio: { required },
        tipo_evento: { required },
        tarifa: { required },
        situacion: { required },
        forma_pago: { required},

        fecha_eventoE: { required },
        responsableE: { required },
        // predio_idE: { required },
        predioE: { required },
        tipo_eventoE: { required },
        tarifaE: { required },


        validationsGroupReg: [
            'responsable',
            'fecha_evento',
            'predio',
            'tipo_evento',
            'tarifa',
            'situacion',
            'forma_pago'
        ],

        validationsGroupMod: [
            'fecha_eventoE',
            'responsableE',
            // 'predio_idE',
            'predioE',
            'tipo_eventoE',
            'tarifaE'
        ],
    },

    mounted() {

        let actual = new Date().getFullYear();

        for (let i = actual - 5; i <= actual + 5; i++) {
            this.anios.push(i);
        }

        this.generarCalendario();
        this.ListarPredio();
        this.ListarTipoEvento();
        this.ListarTarifa();
        this.ListarSituacion();
        this.ListarEvento();
        this.actualizarCalendario();

    },

    computed: {

        formatearFecha() {

            if (!this.fecha_evento) return '';

            let partes = this.fecha_evento.split('-');

            return partes[2] + '/' +
                partes[1] + '/' +
                partes[0];
        }

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

        actualizarCalendario() {
            this.generarCalendario();
            this.ListarEvento();
        },

        seleccionarDia(dia) {
            let fecha =
                this.anio + '-' +
                String(this.mes + 1).padStart(2, '0') + '-' +
                String(dia).padStart(2, '0');
            this.fecha_evento = fecha;
            // OBTENER EVENTOS DEL DIA
            this.eventosDiaSeleccionado = this.obtenerEventosDia(dia);
            // OBTENER IDS DE PREDIOS OCUPADOS
            let prediosOcupados = this.eventosDiaSeleccionado.map(
                evento => evento.predio_id
            );
            // FILTRAR PREDIOS DISPONIBLES
            this.prediosDisponibles = this.arrayPredios.filter(
                predio => !prediosOcupados.includes(predio.id)
            );
            // LIMPIAR CAMPOS
            this.predio = null;
            this.$v.validationsGroupReg.$reset();
            // this.modal = 0;
            $('#ModalEvento').modal('show');
            $(".modal-header").css("background-color", "#007bff");
            $(".modal-header").css("color", "white");
        },

        editarEventoDia(evento_id) {
            axios.post('/mostrarEvento', {
                evento_id: evento_id,
            })
            .then((response) => {
                // =========================================
                // DATOS DEL EVENTO
                // =========================================
                this.arrayMostrarEvento = response.data.eventos;
                this.$v.validationsGroupMod.$reset();
                this.idEventoE = this.arrayMostrarEvento.id;
                this.fecha_eventoE = this.arrayMostrarEvento.fecha_evento;
                this.responsableE = this.arrayMostrarEvento.contratante;
                this.predio_idE = this.arrayMostrarEvento.predio_id;
                this.predioE = this.arrayMostrarEvento.nombre;
                this.tipo_evento_idE = this.arrayMostrarEvento.tipo_evento_id;
                this.tipo_eventoE = this.arrayMostrarEvento.evento;
                this.tarifa_idE = this.arrayMostrarEvento.tarifa_id;
                this.tarifaE = this.arrayMostrarEvento.tarifa;
                this.observacionE = this.arrayMostrarEvento.observacion;
                // =========================================
                // FECHA DEL EVENTO
                // =========================================
                let fecha = this.arrayMostrarEvento.fecha_evento;
                // =========================================
                // OBTENER EVENTOS DE ESA FECHA
                // =========================================
                let eventosDia = this.arrayEvento.filter(
                    evento => evento.fecha_evento == fecha
                );
                // =========================================
                // EXCLUIR EL EVENTO ACTUAL
                // =========================================
                eventosDia = eventosDia.filter(
                    evento => evento.id != evento_id
                );
                // =========================================
                // OBTENER PREDIOS OCUPADOS
                // =========================================
                let prediosOcupados = eventosDia.map(
                    evento => evento.predio_id
                );
                // =========================================
                // FILTRAR PREDIOS DISPONIBLES
                // =========================================
                this.prediosDisponiblesEditar = this.arrayPredios.filter(
                    predio =>
                        !prediosOcupados.includes(predio.id)
                        || predio.id == this.predioE
                );
                // =========================================
                // MOSTRAR MODAL
                // =========================================
                $('#ModalEditar').modal('show');
                $(".modal-header").css("background-color", "#007bff");
                $(".modal-header").css("color", "white");
            })
            .catch((error) => {
                console.log(error);
            });
        },

        Cerrar(valor){
            switch (valor) {
                case 1:
                    this.fecha_evento = '',
                    this.predio = '',
                    this.responsable = '',
                    this.tipo_evento = '',
                    this.tarifa = '',
                    this.situacion = '',
                    this.forma_pago = '',
                    this.observacion = ''
                    break;
                case 2:
                    this.fecha_eventoE = '',
                    this.responsableE = ''
                    break; 
            
                default:
                    break;
            }

            
        },

        ListarPredio() {
            let me = this;
            axios
            .post("/listarPredio", {
            })
            .then(function (response) {
            me.arrayPredios = response.data.predios
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarTipoEvento() {
            let me = this;
            axios
            .post("/listarTipoEvento", {
            })
            .then(function (response) {
            me.arrayTipoEvento = response.data.tipo_eventos
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarTarifa() {
            let me = this;
            axios
            .post("/listarTarifa", {
            })
            .then(function (response) {
            me.arrayTarifa = response.data.tarifas
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarSituacion() {
            let me = this;
            axios
            .post("/listarSituacion", {
            })
            .then(function (response) {
            me.arraySituacion = response.data.situaciones
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarEvento() {
            let me = this;
            axios.post('/listarEvento', {
                anio: me.anio,
                mes: me.mes + 1
            })
            .then(function(response) {
                me.arrayEvento = response.data.eventos;
            });
        },

        obtenerEventosDia(dia) {
            let fecha =
                this.anio + '-' +
                String(this.mes + 1).padStart(2, '0') + '-' +
                String(dia).padStart(2, '0');

            return this.arrayEvento.filter(
                evento => evento.fecha_evento == fecha
            );
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
                            fecha_evento: me.fecha_evento,
                            predio: me.predio.id,
                            responsable: me.responsable.toUpperCase(),
                            tipo_evento: me.tipo_evento.id,
                            tarifa: me.tarifa.id,
                            situacion: me.situacion.id,
                            forma_pago: me.forma_pago,
                            observacion: me.observacion.toUpperCase()
                      })
                      .then(function (response) {
                          swal.fire(
                              "REGISTRADO", //TITULO
                              "Se registro correctamente el evento.", //TEXTO DE MENSAJE
                              "success" // TIPO DE MODAL (success, warnning, error, info)
                          );
                          $('#ModalEvento').modal('hide');
                          me.Cerrar();
                          me.ListarEvento();
                          
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
        },

        EditarEvento(){
            axios.post('/EditarEvento', {
                id: this.idEventoE,
                fecha_evento: this.fecha_eventoE,
                predio_id: this.predioE,
                responsable: this.responsableE
            })
            .then((response) => {

                if(response.data.success){

                    Swal.fire({
                        icon: 'success',
                        title: 'CORRECTO',
                        text: response.data.mensaje
                    });

                    $('#ModalEditar').modal('hide');

                    this.ListarEventos();

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
            });
        },
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

    background: #007bff;

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

.calendar-day {

    min-height: 120px;

    border: 1px solid #dcdcdc;

    padding: 5px;

    cursor: pointer;

}

.evento-calendario {

    color: white;

    font-size: 11px;

    margin-top: 4px;

    padding: 2px 5px;

    border-radius: 4px;

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

}

</style>