<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/dgae_logo.png"> 
    <title>RECIBO</title>
    
    <!-- CSS only -->
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> --}}
    <style>
        @page{
            /*margin: 0rem;
            margin-top:0rem;
            padding: 1rem;*/
            margin: 0cm 0cm;
        }
        body {
            /*margin: 0px;*/
            margin-top: 3cm;
            margin-bottom: 2cm;
            /** margenes de izquierda y derecha es menos un cm*/
            margin-left: 3cm;
            margin-right: 2cm;
            /* border: 1px solid #C00; */
        }
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right:0cm;
            height: 3cm;
            /*background-color: #3498DB;*/
            color: black;
            text-align: right;
            /*line-height: 10px;
            font-size: 9px;
            border: 1px solid #C00;*/
            padding-top: 5px;
            padding-bottom: 0px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 3cm;
            right: 2cm;
            height: 2cm;
            /*background-color: #3498DB;*/
            color: black;
            /*text-align: right;*/
            line-height: 24px;
            /*font-size: 9px;*/
            /* border: 1px solid #C00; */
        }

        * {
            font-family: verdana, arial, sans-serif;
        }
    </style>

</head>
<body>

<header>        
    <div style="padding-top: 0px; padding-bottom: 0px;">
        <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/margin-bottom: 0px; " class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                        <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                            <img style="/*border: 1px solid #C00;*/ width: 100%; height: 100%" src="img/circulo_logo_recibo.png">
                        </div>
                    </td>
                </tr>  
            </tbody>
        </table>
    </div>
</header> 

<footer>
    <div>
        <table width="100%" style="border-collapse: collapse; 
            border-spacing: 0; 
            /* border: black 1px; */
            margin-bottom: 5px;" class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td style="/*border: 1px solid #000;*/ padding-top: 25px; padding-bottom: 25px; font-size: 10px; text-align: left; font-weight: bold;">
                        <span>Fecha de Emisión: 
                            <?php
                                $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
                                $fecha = date('dHi')."-".$meses[date('n')-1]."-".date('Y');
                                echo $fecha;
                            ?>
                        </span>
                    </td>
                    <td style="/*border: 1px solid #000;*/ padding: 0px; font-size: 10px; text-align: right; font-weight: bold;">
                        <span>CIRCULO AERONAUTICO - 
                            <?php
                                $gestion_act = date("Y");
                                echo $gestion_act;
                            ?>
                        </span>
                    </td>
                </tr>  
            </tbody>
        </table>
    </div>
</footer>
    <main>  
    
        <div class="cabecera">
            <!-- <div style="padding-top: 0px; padding-bottom: 1cm;">
                <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/ margin-bottom: 0px; " class="table table-bordered table-striped">
                    <tbody class="table-striped">
                        <tr>
                            <td style="/*border: 1px solid #000;*/ vertical-align: top;">
                                <div style="text-align: center; width: 334px; font-size: 10pt;">
                                    <p style="margin: 1px;">FUERZA AÉREA BOLIVIANA</p>
                                    <p style="margin: 1px;">DIRECCIÓN GENERAL DE AERONAVES DE ESTADO</p>
                                    <p style="margin: 1px;"><u><strong>BOLIVIA</strong></u></p>
                                </div>
                            </td>
                            <td style="/*border: 1px solid #000;*/ vertical-align: top;">
                                <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                                    <img style="/*border: 1px solid #C00;*/ width: 58; height: 58" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
                                </div>
                            </td>
                        </tr>  
                    </tbody>
                </table>
            </div> -->


            <!-- <div style="padding-top: 2px; padding-bottom: 2px; text-align: justify; font-size: 12pt; font-weight: bold; /*border: 1px solid #C00;*/">
                <p style="margin: 1px">EL DIRECTOR GENERAL DE AERONAVES DE ESTADO DE LA FUERZA AÉREA BOLIVIANA</p>
            </div> -->

            <div style="padding-top: 1cm; padding-bottom: 2px; text-align: center; font-size: 14pt; font-weight: bold; /*border: 1px solid #C00;*/">
                <p style="margin: 1px"><u>RECIBO</u></p>
            </div>

            <div style="padding-top: 2px; padding-bottom: 2px; text-align: justify; font-size: 12pt; font-weight: normal; /*border: 1px solid #C00;*/">
                <p style="margin: 1px">En la ciudad de <span style="font-weight: bold;">La Paz</span>, a los <span style="font-weight: bold;">{{ $fecha_det}}</span>, se deja expresa constancia del <span style="font-weight: bold;">{{$situacion_evento->situacion}}</span> por el uso del <span style="font-weight: bold;">{{ $evento->nombre }}</span>, conforme al siguiente detalle:</p>
            </div>

            <div style="padding-top: 2px; padding-bottom: 2px; text-align: justify; font-size: 12pt; font-weight: normal; /*border: 1px solid #C00;*/">
                <p style="margin: 1px"><span style="font-weight: bold;">NOMBRE COMPLETO:</span> {{$evento->contratante}}</p>
                <p style="margin: 1px"><span style="font-weight: bold;">CEDULA DE IDENTIDAD:</span> {{$evento->ci}}</p>
                <p style="margin: 1px"><span style="font-weight: bold;">AMBIENTE RESERVADO:</span> {{$evento->nombre}}</p>
                <?php
                    $date = date_create($evento->fecha_evento);
                    $fechaevento = date_format($date,"d/m/Y");
                ?>
                <p style="margin: 1px"><span style="font-weight: bold;">FECHA DEL EVENTO:</span> {{$fecha_evento_dia}}</p>
                
                <p style="margin: 1px"><span style="font-weight: bold;">TIPO DE EVENTO:</span> {{$evento->evento}}</p>
                <p style="margin: 1px"><span style="font-weight: bold;">TIPO DE TARIFA:</span> {{$evento->tarifa}}</p>
                <!-- <p style="margin: 1px"><span style="font-weight: bold;">MONTO DEPOSITADO: Bs. {{$situacion_evento->monto}}</span></p> -->
            </div>

            <div>
                <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/ margin-bottom: 5px; font-size: 12pt" class="table table-bordered table-striped">
                    <tbody class="table-striped">
                        <tr>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">SITUACIÓN</span></p>
                            </td>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">MONTO (Bs.)</span></p>                            
                            </td>
                        </tr>
                    @if ($situacion_evento->id == 1)
                        @foreach($listar_situacion_evento as $row)
                            <tr>
                                <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                    <p style="margin: 1px"><span style="font-weight: normal;">{{$row->situacion}}</span></p>
                                </td>
                                <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                    <p style="margin: 1px"><span style="font-weight: normal;">{{$row->monto}}</span></p>                            
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">SALDO</span></p>
                            </td>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">{{$evento->precio - $situacion_evento->monto}}</span></p>                            
                            </td>
                        </tr>
                    @else
                        @foreach($listar_situacion_evento as $row)
                            <tr>
                                <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                    <p style="margin: 1px"><span style="font-weight: normal;">{{$row->situacion}}</span></p>
                                </td>
                                <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                    <p style="margin: 1px"><span style="font-weight: normal;">{{$row->monto}}</span></p>                            
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">TOTAL PAGADO</span></p>
                            </td>
                            <td style="/*border: 1px solid #000;*/ padding: 1px; text-align: center;">
                                <p style="margin: 1px"><span style="font-weight: bold;">{{$evento->precio}}</span></p>                            
                            </td>
                        </tr>
                    @endif 
                    </tbody>
                </table>
            </div>

            <div style="padding-top: 2cm; padding-bottom: 2cm; text-align: center; font-size: 12pt; font-weight: bold; /*border: 1px solid #C00;*/">
                <p style="margin: 1px"></p>
            </div>
            
            <div>
                <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/ margin-bottom: 5px;" class="table table-bordered table-striped">
                    <tbody class="table-striped">
                        <tr>
                            <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; text-align: center; font-size: 9pt; vertical-align: top;">
                                <p style="margin: 1px"><span style="font-weight: normal;">HUMBERTO MAMANI PARISACA</span></p>
                                <p style="margin: 1px"><span style="font-weight: bold;">ADMINISTRADOR DEL CIRCULO AÉRONAUTICO</span></p>                            
                            </td>
                            <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; text-align: center; font-size: 9pt; vertical-align: top;">
                                <p style="margin: 1px"><span style="font-weight: normal;">{{$evento->contratante}}</span></p>
                                <p style="margin: 1px"><span style="font-weight: bold;">ARRENDATARIO</span></p>
                                <p style="margin: 1px"><span style="font-weight: bold;">CELULAR:</span> {{ $evento->celular }}</p>                                                      
                            </td>
                        </tr> 
                    </tbody>
                </table>
            </div>
            

            <!-- <div style="padding-top: 1px; padding-bottom: 1px; text-align: right; font-size: 12pt; font-weight: normal; /*border: 1px solid #C00;*/">
                La Paz, 
                    <?php
                        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                        echo date('d')." de ".$meses[date('n')-1]." del ".date('Y');
                    ?>
            </div> -->

            
        </div>
    </main>
    
    <script type="text/php">
            if(isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $pdf->text(310, 760, "$PAGE_NUM - $PAGE_COUNT", $font, 9);
                ');
            }
    </script>

</body>

</html>
