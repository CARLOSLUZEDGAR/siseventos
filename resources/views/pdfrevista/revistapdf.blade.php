<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE REVISTAS</title>
    <!-- CSS only -->
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
            margin-left: 2cm;
            margin-right: 1cm;
            /**border: 1px solid #C00;*/
        }
        header {
            position: fixed;
            top: 0cm;
            left: 3cm;
            right:2cm;
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
            /*font-size: 9px;
            border: 1px solid #C00;*/
        }

        /*#footer .page:after { content: counter(page, upper-roman);}*/

        * {
            font-family: verdana, arial, sans-serif;
        }
        .cabecera {
            background-color: #FEFEFE;
            color: #000000;
            padding: 2rem;
            padding-top: .2rem;
            padding-bottom: 0px;
        }
        .cabecera .logo{
            margin: 5px;
        }
        .cabecera table {
            padding: 1px;
            margin-bottom: .2rem;
        }
        table {
            font-size: x-small
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }

    </style>
</head>
<body>

<header>
    <div style="padding-top: 22px; padding-bottom: 1px;">
        <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/margin-bottom: 0px; " class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                        <div style="text-align: center; width: 300px; font-size: 11.5px; /*border: 1px solid #C00;*/">
                            <p style="margin: 1px;">FUERZA AÉREA BOLIVIANA</p>
                            <p style="margin: 1px;">DEPARTAMENTO III - OPERACIONES EMGFAB</p>
                            <p style="margin: 1px;"><u><strong>BOLIVIA</strong></u></p>
                        </div>
                    </td>
                    <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                        <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                            <img style="/*border: 1px solid #C00;*/ width: 58; height: 58" src="data:image/png;base64, {!! base64_encode($qrband) !!} ">
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
                        <span>Fecha de Emision:
                            <?php
                                $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
                                $fecha = date('dHi')."-".$meses[date('n')-1]."-".date('Y');
                                echo $fecha;
                            ?>
                        </span>
                    </td>
                    <td style="/*border: 1px solid #000;*/ padding: 2px; font-size: 10px; text-align: right; font-weight: bold;">
                        <span>Desarrollado por la: DIGETIC -
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
                <div style="padding-top: 30px; padding-bottom: 30px; text-align: center; font-size: 15px; font-weight: bold; /*border: 1px solid #C00;*/">
                    <p style="margin: 1px">REPORTES INDIVIDUALES</p>
                </div>
                <div style="padding-top: 5px; padding-bottom: 5px; text-align: left; font-size: 12px; font-weight: bold; /*border: 1px solid #C00;*/">

                    <p style="margin: 1px">NOMBRE: {{$nombrepersona->grado}} {{$nombrepersona->complemento}} {{$nombrepersona->per_nombre}} {{$nombrepersona->per_paterno}} {{$nombrepersona->per_materno}} </p><br>

                    <p  style="margin: 1px">CARNET MILITAR:{{$nombrepersona->per_cm}}</p>
                </div>
                <br>

            <table width="100%" style="border-collapse: collapse;
            border-spacing: 0;
            border: black 1px;
            margin-bottom: 5px;" class="table table-bordered table-striped">
                <thead style="background-color: lightgray;">
                    <tr>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">Nº</th>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">CARGO</th>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">COMPAÑIA</th>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">FECHA DE REVISTA</th>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">UNIDAD</th>
                        <th style="text-align: center; border: 1px solid #000; height:30px;">DELEGADO REVISTANTE</th>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <?php
                        $x = 0;
                    ?>
                    @foreach ($revistapersonal as $rp)
                    @if($rp->nro_doc == '')
                    <?php
                        $x = $x + 1;
                    ?>
                    <tr>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px; text-align: center;"><span><?php echo $x; ?></span></td>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px;">{{$rp->funcion}}</td>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px;">{{$rp->compania}}</td>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px;">{{$rp->fecha}}</td>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px;">{{$rp->lugar}}</td>
                        <td style="border: 1px solid #000; padding: 7px; font-size: 10px;">{{$rp->delegado_revistante}}</td>
                    </tr>
                    @endif
                    @endforeach

                </tbody>
            </table>
            <div style="padding-top: 5px; padding-bottom: 5px; text-align: right; font-size: 12px; /*font-weight: bold; border: 1px solid #C00;*/">
                La Paz, <?php
                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    echo date('d')." de ".$meses[date('n')-1]." del ".date('Y');
                    ?>

            </div>

            <div style="padding-top: 100px; padding-bottom: 0px; text-align: center; /*font-size: 14px; font-weight: bold; border: 1px solid #C00;*/">
                <p style="margin: 1px; font-size: 14px;">CNL. DAEN. GERARDO ZABALA ALVAREZ</p>
                <p style="margin: 1px; font-size: 14px; font-weight: bold;">JEFE DEL DEPARTAMENTO III - OPERACIONES EMGFAB</p>
            </div>
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
