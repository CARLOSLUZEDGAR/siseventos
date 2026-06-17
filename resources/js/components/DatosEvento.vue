<template>
    <div class="container">

        <div class="row mb-4">

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
                        <!-- {{ evento.nombre }} -->
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEvento">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header">
                            <h4 class="modal-title">
                                FECHA: {{ formatearFecha }}
                            </h4>
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
                                            <!-- {{ evento.fecha_evento }} -->
                                            {{ formatearFecha }}
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
                                            <label class="form-control-label">
                                                CEDULA IDENTIDAD:
                                            </label>
                                            <input type="text" class="form-control" v-model="ci" style="text-transform:uppercase;" :class="{'is-invalid' : $v.ci.$error, 'is-valid': !$v.ci.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.ci.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-control-label">
                                                CELULAR:
                                            </label>
                                            <input type="text" class="form-control" v-model="celular" style="text-transform:uppercase;" :class="{'is-invalid' : $v.celular.$error, 'is-valid': !$v.celular.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.celular.required">
                                                    Este campo es requerido
                                                </span>
                                                <span v-else-if="!$v.celular.numeric">
                                                    Solo digitos
                                                </span>
                                                <span v-else-if="!$v.celular.length">
                                                    Debe contener 8 digitos
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4" v-if="situacion && situacion.id == 1">
                                            <label class="form-control-label">
                                                MONTO (Bs.):
                                            </label>
                                            <input type="number" step="0.01" min="0" :max="tarifa ? tarifa.precio : null" class="form-control" v-model.number="monto" :class="{'is-invalid' : $v.monto.$error, 'is-valid': !$v.monto.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.monto.required">
                                                    Este campo es requerido
                                                </span>
                                                <span v-if="$v.monto.required && !$v.monto.decimalDos">
                                                    Debe ingresar un monto válido con máximo 2 decimales
                                                </span>
                                                <span v-else-if="!$v.monto.montoMaximo">
                                                    El monto no puede ser mayor a {{ tarifa.precio }} Bs.
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
                                        <button type="button" class="btn btn-primary" @click="Guardar()" :disabled="procesando">{{ procesando ? 'Procesando...' : 'GUARDAR' }}</button>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEditar">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header">
                            <h4 class="modal-title">
                                EDITAR EVENTO
                            </h4>
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
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>SITUACIÓN</th>
                                                        <th>MONTO (Bs.)</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="situacion_idE == 1">
                                                    <tr>
                                                        <td>{{ situacionE }}</td>
                                                        <td>{{ montoE }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>SALDO</td>
                                                        <td style="text-align: right;">
                                                            {{ (Number(precio) - Number(montoE)).toFixed(2) }}
                                                            <button type="button" class="btn btn-danger mr-2" @click="PagarSaldo(id_eventoE)" :disabled="procesando">{{ procesando ? 'Procesando...' : 'PAGAR SALDO' }}</button>  
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>TOTAL</td>
                                                        <td>{{ precio }}</td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr v-for="se in arraySituacionEvento">
                                                        <td>{{ se.situacion }}</td>
                                                        <td style="text-align: right;">{{ se.monto }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;">TOTAL</td>
                                                        <td style="text-align: right;">{{ precio }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
                                        <button type="button" class="btn btn-primary" @click="Editar()" :disabled="procesando">{{ procesando ? 'Procesando...' : 'EDITAR' }}</button>
                                    </div>  
                                </div>
                            </div>
                            
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { required, requiredIf, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";

const decimalDos = value => {
    if (!value) return true;

    return /^\d+(\.\d{1,2})?$/.test(value);
};

const montoMaximo = function(value) {

    if (!value) return true;

    if (!this.situacion || this.situacion.id != 1) {
        return true;
    }

    return Number(value) <= Number(this.tarifa.precio);
};

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
            ci: '',
            celular: '',
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
            monto: '',
            observacion: '',
            arrayEvento: [],
            eventosDiaSeleccionado: [],
            prediosDisponibles: [],
            arrayMostrarEvento: [],

            id_eventoE: '',
            fecha_eventoE: '',
            responsableE: '',
            ciE: '',
            celularE: '',
            predio_idE: '',
            predioE: '',
            tipo_evento_idE: '',
            tipo_eventoE: '',
            tarifa_idE: '',
            tarifaE: '',
            precio: '',
            situacion_idE: '',
            situacionE: '',
            montoE: '',
            observacionE: '',
            arraySituacionEvento: [],
            prediosDisponiblesEditar: [],

            procesando: false,
        }
    },

    validations: { 
        responsable: { required },
        ci: { required },
        celular: { required, numeric, hasSpecificLength: value => value && value.toString().length === 8 },
        fecha_evento: { required },
        predio: { required },
        tipo_evento: { required },
        tarifa: { required },
        situacion: { required },
        forma_pago: { required },
        monto: { required: requiredIf(function () {
            return this.situacion && this.situacion.id == 1;
        }),
        decimalDos, montoMaximo },

        fecha_eventoE: { required },
        responsableE: { required },
        ciE: { required },
        celularE: { required },
        // predio_idE: { required },
        predioE: { required },
        tipo_eventoE: { required },
        tarifaE: { required },
        situacionE: { required },
        montoE: { required },


        validationsGroupReg: [
            'responsable',
            'ci',
            'celular',
            'fecha_evento',
            'predio',
            'tipo_evento',
            'tarifa',
            'situacion',
            'forma_pago',
            'monto'
        ],

        validationsGroupMod: [
            'fecha_eventoE',
            'responsableE',
            'ciE',
            'celularE',
            // 'predio_idE',
            'predioE',
            'tipo_eventoE',
            'tarifaE',
            'situacionE',
            'montoE'
        ],
    },

    watch: {
        situacion(valor) {
            if (!valor || valor.id != 1) {
                this.monto = null;
                this.$v.monto.$reset();
            }
        }
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
                this.arraySituacionEvento = response.data.situacion_evento;
                this.$v.validationsGroupMod.$reset();
                this.id_eventoE = this.arrayMostrarEvento.id;
                this.fecha_eventoE = this.arrayMostrarEvento.fecha_evento;
                this.responsableE = this.arrayMostrarEvento.contratante;
                this.predio_idE = this.arrayMostrarEvento.predio_id;
                this.predioE = this.arrayMostrarEvento.nombre;
                this.tipo_evento_idE = this.arrayMostrarEvento.tipo_evento_id;
                this.tipo_eventoE = this.arrayMostrarEvento.evento;
                this.tarifa_idE = this.arrayMostrarEvento.tarifa_id;
                this.tarifaE = this.arrayMostrarEvento.tarifa;
                this.situacion_idE = this.arrayMostrarEvento.situacion_id;
                this.situacionE = this.arrayMostrarEvento.situacion;
                this.montoE =   this.arrayMostrarEvento.monto;
                this.observacionE = this.arrayMostrarEvento.observacion;
                this.precio = this.arrayMostrarEvento.precio;
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
                    this.ci = '',
                    this.celular = '',
                    this.tipo_evento = '',
                    this.tarifa = '',
                    this.situacion = '',
                    this.forma_pago = '',
                    this.observacion = '',
                    this.monto = ''
                    break;
                case 2:
                    this.fecha_eventoE = '',
                    this.responsableE = '',
                    this.ciE = '',
                    this.celularE = '',
                    this.predioE = '',
                    this.tipo_eventoE = '',
                    this.tarifaE = '',
                    this.situacionE = '',
                    this.observacionE = '',
                    this.montoE = ''
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
            if (this.procesando) {
                return;
            }

             if (!this.$v.validationsGroupReg.$invalid) {
                swal.fire({
                    title: '¿Desea registrar este evento?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'info',
                    cancelButtonColor: '#868077',
                    confirmButtonText: 'Confirmar/Generar Recibo',
                    cancelButtonText: 'Cancelar',
                    buttonsStyling: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        this.procesando = true;
                        axios.post('/registrarEvento', {
                            fecha_evento: this.fecha_evento,
                            predio_id: this.predio.id,
                            responsable: this.responsable.toUpperCase(),
                            ci: this.ci,
                            celular: this.celular,
                            tipo_evento_id: this.tipo_evento.id,
                            tarifa_id: this.tarifa.id,
                            situacion_id: this.situacion.id,
                            forma_pago: this.forma_pago,
                            monto: this.situacion.id == 1
                                ? this.monto
                                : this.tarifa.precio,
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
                                $('#ModalEvento').modal('hide');
                                this.Cerrar(1);
                                this.ListarEvento();
                                this.GenerarContrato(response.data.evento.id, response.data.situacion_evento.id);
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
                        $('#ModalEvento').modal('hide');
                        this.Cerrar(1);
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
                    title: '¿Desea editar este evento?', // TITULO 
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
                        axios.post('/editarEvento', {
                            id_evento: this.id_eventoE,
                            fecha_evento: this.fecha_eventoE,
                            predio_id: this.predio_idE,
                            observacion: this.observacionE.toUpperCase()
                            // responsable: this.responsableE
                        })
                        .then((response) => {
                            if(response.data.success){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });
                                $('#ModalEditar').modal('hide');
                                $('#ModalEvento').modal('hide');
                                this.Cerrar(2);
                                this.ListarEvento();
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
                        $('#ModalEditar').modal('hide');
                        me.Cerrar(2);
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

        PagarSaldo(idEvento){
            if (this.procesando) {
                return;
            }
        //    if(!this.$v.validationsGroupSaldo.$invalid){
                swal.fire({
                    title: '¿Desea pagar el saldo de este evento?', // TITULO 
                    icon: 'question', //ICONO (success, warnning, error, info, question)
                    showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
                    confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
                    cancelButtonColor: '#868077', // CLOR DEL BOTON CANCELAR
                    confirmButtonText: 'Confirmar/Generar Recibo', //TITULO DEL BOTON CONFIRMAR
                    cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
                    buttonsStyling: true,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        this.procesando = true;
                        axios.post('/pagarSaldoEvento', {
                            id_evento: idEvento,
                            precio: this.precio,
                            adelanto: this.montoE
                            // fecha_evento: this.fecha_eventoE,
                            // predio_id: this.predio_idE,
                            // observacion: this.observacionE.toUpperCase()
                            // responsable: this.responsableE
                        })
                        .then((response) => {
                            if(response.data.success){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });

                                $('#ModalEditar').modal('hide');
                                // $('#ModalEvento').modal('hide');
                                this.Cerrar(2);
                                this.ListarEvento();
                                this.GenerarContrato(response.data.situacion_evento.evento_id, response.data.situacion_evento.id);
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
                        $('#ModalEditar').modal('hide');
                        me.Cerrar(2);
                    }
                })
            // }else{
            //     this.$v.validationsGroupMod.$touch();
            //     Swal.fire({
            //         icon: 'warning',
            //         title: 'Ingrese todos los datos requeridos',
            //         showConfirmButton: false,
            //         timer: 2000
            //     }) 
            // } 
        },

        GenerarContrato(idEvento, idSituacionEvento){
            window.open('/contrato?idE='+idEvento+'&idSE='+idSituacionEvento);
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

/* .v-select,
.vs__dropdown-toggle {
    width: 100%;
} */

</style>