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
                    <!-- NÚMERO DÍA -->
                    <strong>{{ dia }}</strong>
                    <!-- EVENTOS -->
                    <div
                        v-for="(evento, index) in obtenerEventosDia(dia)"
                        :key="index"
                        class="evento-item"
                    >
                        <!-- PUNTO DE COLOR -->
                        <span
                            class="evento-punto"
                            :style="{
                                backgroundColor: evento.color
                            }"
                        ></span>
                        <!-- HORARIO -->
                        <span class="evento-hora">
                            {{ evento.hora_inicio ? evento.hora_inicio.slice(0,5) : '--:--' }} - {{ evento.hora_fin ? evento.hora_fin.slice(0,5) : '--:--' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEvento">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                                    <button type="button" class="btn btn-primary" @click="editarEventoDia(evento.id)">CAMBIAR FECHA/PAGAR SALDO</button>
                                </div>
                            </div>
                        </div>
                        <!-- FORMULARIO -->
                        <!-- <div v-if="prediosDisponibles.length > 0"> -->
                            <hr>
                            <h5 class="mb-3">
                                REGISTRAR NUEVO EVENTO
                            </h5>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label class="form-control-label">
                                                ARRENDATARIO:
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                FECHA INICIO:
                                            </label>
                                            <input type="text" class="form-control" :value="formatearFecha" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                HORA INICIO:
                                            </label>
                                            <select class="form-control" v-model="hora_inicio" :class="{'is-invalid' : $v.hora_inicio.$error, 'is-valid': !$v.hora_inicio.$invalid}">
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="hora in horasDisponibles"
                                                    :key="'inicio-' + hora"
                                                    :value="hora"
                                                >
                                                    {{ hora }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.hora_inicio.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                FECHA FIN:
                                            </label>
                                            <input type="date" class="form-control" v-model="fecha_evento_fin" :min="fecha_evento" :class="{'is-invalid' : $v.fecha_evento_fin.$error, 'is-valid': !$v.fecha_evento_fin.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.fecha_evento_fin.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                HORA FIN:
                                            </label>
                                            <select class="form-control" v-model="hora_fin" :class="{'is-invalid' : $v.hora_fin.$error, 'is-valid': !$v.hora_fin.$invalid}">
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="hora in horasDisponibles"
                                                    :key="'fin-' + hora"
                                                    :value="hora"
                                                >
                                                    {{ hora }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.hora_fin.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label>TIPO PREDIO:</label>
                                            <select
                                                class="form-control"
                                                v-model="tipo_predio"
                                                @change="ListarPredio(tipo_predio), ListarTipoEvento(tipo_predio)"
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
                                        <div class="col-md-4">
                                            <label>PREDIO:</label>
                                            <select
                                                class="form-control"
                                                v-model="predio"
                                                :class="{
                                                    'is-invalid': $v.predio.$error,
                                                    'is-valid': !$v.predio.$invalid
                                                }"
                                            >
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="item in arrayPredios"
                                                    :key="item.id"
                                                    :value="item"
                                                >
                                                    {{ item.nombre }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.predio.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>TIPO EVENTO:</label>
                                            <select
                                                class="form-control"
                                                v-model="tipo_evento"
                                                :class="{
                                                    'is-invalid': $v.tipo_evento.$error,
                                                    'is-valid': !$v.tipo_evento.$invalid
                                                }"
                                            >
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="item in arrayTipoEvento"
                                                    :key="item.id"
                                                    :value="item"
                                                >
                                                    {{ item.evento }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tipo_evento.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label>TARIFA:</label>
                                            <select
                                                class="form-control"
                                                v-model="tarifa"
                                                :class="{
                                                    'is-invalid': $v.tarifa.$error,
                                                    'is-valid': !$v.tarifa.$invalid
                                                }"
                                            >
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="item in arrayTarifa"
                                                    :key="item.id"
                                                    :value="item"
                                                >
                                                    {{ item.tarifa }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tarifa.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>SITUACIÓN:</label>
                                            <select
                                                class="form-control"
                                                v-model="situacion"
                                                :class="{
                                                    'is-invalid': $v.situacion.$error,
                                                    'is-valid': !$v.situacion.$invalid
                                                }"
                                            >
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="item in arraySituacion"
                                                    :key="item.id"
                                                    :value="item"
                                                >
                                                    {{ item.situacion }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.situacion.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>FORMA DE PAGO:</label>
                                            <select
                                                class="form-control"
                                                v-model="forma_pago"
                                                :class="{
                                                    'is-invalid': $v.forma_pago.$error,
                                                    'is-valid': !$v.forma_pago.$invalid
                                                }"
                                            >
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="(item, index) in arrayFormaPago"
                                                    :key="index"
                                                    :value="item"
                                                >
                                                    {{ item }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.forma_pago.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4" v-if="situacion && situacion.id == 1">
                                            <label class="form-control-label">
                                                MONTO (Bs.):
                                            </label>
                                            <input type="number" step="0.01" min="0" :max="predio && tarifa ? Number(((predio.precio * (100 - tarifa.porcentaje)) / 100).toFixed(2)) : null" class="form-control" v-model.number="monto" :class="{'is-invalid' : $v.monto.$error, 'is-valid': !$v.monto.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.monto.required">
                                                    Este campo es requerido
                                                </span>
                                                <span v-if="$v.monto.required && !$v.monto.decimalDos">
                                                    Debe ingresar un monto válido con máximo 2 decimales
                                                </span>
                                                <span v-else-if="!$v.monto.montoMaximo">
                                                    El monto no puede ser mayor a {{ (predio.precio * (100 - tarifa.porcentaje))/100 }} Bs.
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
                            
                        <!-- </div> -->
                        <!-- TODOS OCUPADOS -->
                        <!-- <div v-else class="alert alert-danger mt-3">
                            TODOS LOS SALONES YA ESTÁN OCUPADOS
                            PARA ESTA FECHA.
                        </div> -->
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Cerrar(1)">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="ModalEditar">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                                            <label class="form-control-label">
                                                CEDULA IDENTIDAD:
                                            </label>
                                            <input type="text" class="form-control" v-model="ciE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.ciE.$error, 'is-valid': !$v.ciE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.ciE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                CELULAR:
                                            </label>
                                            <input type="text" class="form-control" v-model="celularE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.celularE.$error, 'is-valid': !$v.celularE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.celularE.required">
                                                    Este campo es requerido
                                                </span>
                                                <span v-else-if="!$v.celularE.numeric">
                                                    Solo digitos
                                                </span>
                                                <span v-else-if="!$v.celularE.length">
                                                    Debe contener 8 digitos
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                FECHA INICO:
                                            </label>
                                            <input type="date" class="form-control" v-model="fecha_eventoE" :class="{'is-invalid' : $v.fecha_eventoE.$error, 'is-valid': !$v.fecha_eventoE.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.fecha_eventoE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                HORA INICIO:
                                            </label>
                                            <select class="form-control" v-model="hora_inicioE" :class="{'is-invalid' : $v.hora_inicioE.$error, 'is-valid': !$v.hora_inicioE.$invalid}">
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="hora in horasDisponibles"
                                                    :key="'inicio-' + hora"
                                                    :value="hora"
                                                >
                                                    {{ hora }}
                                                </option>
                                            </select>                                            
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.hora_inicioE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                FECHA FIN:
                                            </label>
                                            <input type="date" class="form-control" v-model="fecha_evento_finE" :min="fecha_eventoE" :class="{'is-invalid' : $v.fecha_evento_finE.$error, 'is-valid': !$v.fecha_evento_finE.$invalid}">
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.fecha_evento_finE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-control-label">
                                                HORA FIN:
                                            </label>
                                            <select class="form-control" v-model="hora_finE" :class="{'is-invalid' : $v.hora_finE.$error, 'is-valid': !$v.hora_finE.$invalid}">
                                                <option value="">SELECCIONE...</option>
                                                <option
                                                    v-for="hora in horasDisponibles"
                                                    :key="'inicio-' + hora"
                                                    :value="hora"
                                                >
                                                    {{ hora }}
                                                </option>
                                            </select>                                              
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.hora_finE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label>TIPO PREDIO:</label>
                                            <input type="text" class="form-control" v-model="tipo_predioE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.tipo_predioE.$error, 'is-valid': !$v.tipo_predioE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tipo_predioE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>PREDIO:</label>
                                            <input type="text" class="form-control" v-model="predioE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.predioE.$error, 'is-valid': !$v.predioE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.predioE.required">
                                                    Este campo es requerido
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>TIPO EVENTO:</label>
                                            <input type="text" class="form-control" v-model="tipo_eventoE" style="text-transform:uppercase;" :class="{'is-invalid' : $v.tipo_eventoE.$error, 'is-valid': !$v.tipo_eventoE.$invalid}" disabled>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.tipo_eventoE.required">
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
                                                        <th style="text-align: center; vertical-align: middle;">SITUACIÓN (TARIFA: {{ tarifaE }})</th>
                                                        <th style="text-align: center; vertical-align: middle;">MONTO (Bs.)</th>
                                                        <th style="text-align: center; vertical-align: middle;" v-if="situacion_idE == 1">PAGO</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="situacion_idE == 1">
                                                    <tr>
                                                        <td style="text-align: left; vertical-align: middle;">{{ situacionE }}</td>
                                                        <td style="text-align: right; vertical-align: middle;">{{ Number(montoE).toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; vertical-align: middle;">SALDO</td>
                                                        <td style="text-align: right; vertical-align: middle;">
                                                            {{ (((Number(precio) * (100 - Number(porcentaje)))/100) - Number(montoE)).toFixed(2) }}
                                                        </td>
                                                        <td>
                                                            <div class="col-md-12">
                                                                <label>FORMA DE PAGO:</label>
                                                                <select
                                                                    class="form-control"
                                                                    v-model="forma_pagoE"
                                                                    :class="{
                                                                        'is-invalid': $v.forma_pagoE.$error,
                                                                        'is-valid': !$v.forma_pagoE.$invalid
                                                                    }"
                                                                >
                                                                    <option value="">SELECCIONE...</option>
                                                                    <option
                                                                        v-for="(item, index) in arrayFormaPago"
                                                                        :key="index"
                                                                        :value="item"
                                                                    >
                                                                        {{ item }}
                                                                    </option>
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    <span v-if="!$v.forma_pagoE.required">
                                                                        Este campo es requerido
                                                                    </span>
                                                                </div>
                                                            </div><br>
                                                            <button type="button" class="btn btn-danger mr-2" @click="PagarSaldo(id_eventoE)" :disabled="procesando">{{ procesando ? 'Procesando...' : 'PAGAR SALDO' }}</button>  
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; vertical-align: middle;">TOTAL</td>
                                                        <td style="text-align: right; vertical-align: middle;">{{ ((Number(precio) * (100 - Number(porcentaje)))/100).toFixed(2) }}</td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr v-for="se in arraySituacionEvento">
                                                        <td style="text-align: left; vertical-align: middle;">{{ se.situacion }}</td>
                                                        <td style="text-align: right; vertical-align: middle;">{{ Number(se.monto).toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left; vertical-align: middle;">TOTAL</td>
                                                        <td style="text-align: right; vertical-align: middle;">{{ ((Number(precio) * (100 - Number(porcentaje)))/100).toFixed(2) }}</td>
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
                                        <button type="button" class="btn btn-primary" @click="Editar()" :disabled="procesando">{{ procesando ? 'Procesando...' : 'CAMBIAR FECHA' }}</button>
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

    return Number(value) <= Number(((this.predio.precio * (100 - this.tarifa.porcentaje))/100));
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
            // fecha_evento_fin: new Date().toISOString().slice(0, 10),
            fecha_evento_fin: '',
            hora_inicio: '',
            hora_fin: '',
            arrayTipoPredios: [],
            arrayPredios: [],
            tipo_predio: '',
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
            horasDisponibles: [],

            id_eventoE: '',
            fecha_eventoE: '',
            fecha_evento_finE: '',
            hora_inicioE: '',
            hora_finE: '',
            responsableE: '',
            ciE: '',
            celularE: '',
            predio_idE: '',
            tipo_predioE: '',
            predioE: '',
            tipo_evento_idE: '',
            tipo_eventoE: '',
            tarifa_idE: '',
            forma_pagoE: '',
            tarifaE: '',
            precio: '',
            porcentaje: '',
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
        fecha_evento_fin: { required },
        hora_inicio: { required },
        hora_fin: { required },
        tipo_predio: { required },
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
        fecha_evento_finE: { required },
        hora_inicioE: { required },
        hora_finE: { required },
        responsableE: { required },
        ciE: { required },
        celularE: { required },
        // predio_idE: { required },
        tipo_predioE: { required },
        predioE: { required },
        tipo_eventoE: { required },
        tarifaE: { required },
        situacionE: { required },
        montoE: { required },

        forma_pagoE: { required },


        validationsGroupReg: [
            'responsable',
            'ci',
            'celular',
            'fecha_evento',
            'fecha_evento_fin',
            'hora_inicio',
            'hora_fin',
            'tipo_predio',
            'predio',
            'tipo_evento',
            'tarifa',
            'situacion',
            'forma_pago',
            'monto'
        ],

        validationsGroupMod: [
            'fecha_eventoE',
            'fecha_evento_finE',
            'hora_inicioE',
            'hora_finE',
            'responsableE',
            'ciE',
            'celularE',
            // 'predio_idE',
            'tipo_predioE',
            'predioE',
            'tipo_eventoE',
            'tarifaE',
            'situacionE',
            'montoE'
        ],

        validationGroupPagoSaldo: [
            'forma_pagoE'
        ]
    },

    watch: {
        situacion(valor) {
            if (!valor || valor.id != 1) {
                this.monto = null;
                this.$v.monto.$reset();
            }
        },

        fecha_evento_fin(valor) {

            if (valor && valor < this.fecha_evento) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha inválida',
                    text: 'La fecha fin no puede ser menor a la fecha inicio'
                });
                this.fecha_evento_fin = '';
                return;
            }
            this.validarHoras();
        },

        hora_inicio() {
            this.validarHoras();
        },

        hora_fin() {
            this.validarHoras();
        },

        fecha_evento_finE(valor) {

            if (valor && valor < this.fecha_eventoE) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha inválida',
                    text: 'La fecha fin no puede ser menor a la fecha inicio'
                });
                this.fecha_evento_finE = '';
                return;
            }
            this.validarHorasE();
        },

        hora_inicioE() {
            this.validarHorasE();
        },

        hora_finE() {
            this.validarHorasE();
        },
    },

    mounted() {
        let actual = new Date().getFullYear();
        for (let i = actual - 5; i <= actual + 5; i++) {
            this.anios.push(i);
        }

        for (let j = 0; j <= 24; j++) {
            this.horasDisponibles.push(
                String(j).padStart(2, '0') + ':00'
            );
            // this.horasDisponibles.push(
            //     String(j).padStart(2, '0') + ':30'
            // );
        }

        this.generarCalendario();
        this.ListarTipoPredio();
        this.ListarPredio(this.tipo_predio);
        this.ListarTipoEvento(this.tipo_predio);
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
            this.predio = '';
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
                this.fecha_evento_finE = this.arrayMostrarEvento.fecha_evento_fin;
                this.responsableE = this.arrayMostrarEvento.contratante;
                this.ciE = this.arrayMostrarEvento.ci;
                this.celularE = this.arrayMostrarEvento.celular;
                this.predio_idE = this.arrayMostrarEvento.predio_id;
                this.tipo_predioE = this.arrayMostrarEvento.clasificacion;
                this.predioE = this.arrayMostrarEvento.nombre;
                this.tipo_evento_idE = this.arrayMostrarEvento.tipo_evento_id;
                this.tipo_eventoE = this.arrayMostrarEvento.evento;
                this.hora_inicioE = this.arrayMostrarEvento.hora_inicio.slice(0, 5);
                this.hora_finE = this.arrayMostrarEvento.hora_fin.slice(0, 5);
                this.tarifa_idE = this.arrayMostrarEvento.tarifa_id;
                this.tarifaE = this.arrayMostrarEvento.tarifa;
                this.situacion_idE = this.arrayMostrarEvento.situacion_id;
                this.situacionE = this.arrayMostrarEvento.situacion;
                this.montoE =   this.arrayMostrarEvento.monto;
                this.observacionE = this.arrayMostrarEvento.observacion;
                this.precio = this.arrayMostrarEvento.precio;
                this.porcentaje = this.arrayMostrarEvento.porcentaje;
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
                    this.fecha_evento_fin = '',
                    this.hora_inicio = '',
                    this.hora_fin = '',
                    this.tipo_predio = '',
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
                    this.fecha_evento_finE = '',
                    this.hora_inicioE = '',
                    this.hora_finE = '',
                    this.responsableE = '',
                    this.ciE = '',
                    this.celularE = '',
                    this.tipo_predioE = '',
                    this.predioE = '',
                    this.tipo_eventoE = '',
                    this.tarifaE = '',
                    this.situacionE = '',
                    this.observacionE = '',
                    this.montoE = '',
                    this.precio = '',
                    this.porcentaje = '',
                    this.forma_pagoE = ''
                    break; 
            
                default:
                    break;
            }
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

        ListarPredio(tipo_predio) {
            let me = this;
            me.predio = '';
            me.arrayPredios = [];
            axios
            .post("/listarPredio", {
                tipo_predio_id : tipo_predio,
            })
            .then(function (response) {
            me.arrayPredios = response.data.predios
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarTipoEvento(tipo_predio) {
            let me = this;
            me.tipo_evento = '';
            me.arrayTipoEvento = [];
            axios
            .post("/listarTipoEvento", {
                tipo_predio_id : tipo_predio,
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

        validarHoras() {
            if (
                this.fecha_evento_fin === this.fecha_evento &&
                this.hora_inicio &&
                this.hora_fin &&
                this.hora_fin <= this.hora_inicio
            ) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hora inválida',
                    text: 'La hora fin debe ser mayor a la hora inicio cuando ambas fechas son iguales'
                });
                this.hora_fin = '';
            }
        },

        validarHorasE() {
            if (
                this.fecha_evento_finE === this.fecha_eventoE &&
                this.hora_inicioE &&
                this.hora_finE &&
                this.hora_finE <= this.hora_inicioE
            ) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hora inválida',
                    text: 'La hora fin debe ser mayor a la hora inicio cuando ambas fechas son iguales'
                });
                this.hora_finE = '';
            }
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
                            fecha_evento_fin: this.fecha_evento_fin,
                            hora_inicio: this.hora_inicio,
                            hora_fin: this.hora_fin,
                            tipo_predio_id: this.tipo_predio.id,
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
                                : (this.predio.precio * this.tarifa.porcentaje)/100,
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
                            hora_inicio: this.hora_inicioE,
                            fecha_evento_fin: this.fecha_evento_finE,
                            hora_fin: this.hora_finE,
                            predio_id: this.predio_idE,
                            // observacion: this.observacionE.toUpperCase()
                            observacion: this.observacionE
                                ? this.observacionE.toUpperCase()
                                : ''
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
            if(!this.$v.validationGroupPagoSaldo.$invalid){
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
                            precio: (this.precio * this.porcentaje)/100,
                            adelanto: this.montoE,
                            forma_pago: this.forma_pagoE
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
            }else{
                this.$v.validationGroupPagoSaldo.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                }) 
            } 
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

/* .evento-punto {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin: 2px auto;
} */

.v-select,
.vs__dropdown-toggle {
    width: 100%;
}

.evento-item {
    display: flex;
    align-items: center;
    margin-top: 2px;
    font-size: 12px;
}

.evento-punto {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin-right: 5px;
    flex-shrink: 0;
}

.evento-hora {
    line-height: 1;
}

</style>