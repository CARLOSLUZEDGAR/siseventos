<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page{
            /* margin: 1.5rem;
            margin-top:1.9rem;
            padding: 1rem; */
            margin: 0px 0px 0px 0px !important;
            padding: 0px 0px 0px 0px !important;
        }
        html {
            /*margin: 20pt 25pt;/*SUPERIOR INFERIOR Y DERECHA IZQUIERDA*/
            margin: 0px;
        }
        /* body { */
        /*position: relative;*/
         /* width: 21.6cm;   
        height: 37cm;*/
        /* margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        /* margin-top: 0.5cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 1cm; */
        /* font-family: Arial, sans-serif;  */
        /* font-size: 12.3px; */
        /*font-family: SourceSansPro;*/
        /* } */
        body {
            /*margin: 0px;*/
            margin-top: 3cm;
            margin-bottom: 2cm;
            /** margenes de izquierda y derecha es menos un cm*/
            margin-left: 0.7cm;
            margin-right: 0.7cm;
            /**border: 1px solid #C00;*/
        }
        header {
            position: fixed;
            top: 0cm;
            left: 3cm;
            right:2cm;
            height: 2cm;
            /*background-color: #3498DB;*/
            color: black;
            text-align: right;
            /* line-height: 10px;
            font-size: 9px; */
            /* border: 1px solid #C00; */
            padding-top: 5px;
            padding-bottom: 0px;
        }
        footer {
            position: fixed;
            bottom: 0cm;
            left: 3cm;
            right: 2cm;
            height: 1.5cm;
            /*background-color: #3498DB;*/
            color: black;
            /*text-align: right;*/
            line-height: 14px;
            /* font-size: 9px; */
            /* border: 1px solid #C00; */
        }
        * {
            font-family: verdana, arial, sans-serif;
        }
        .cabecera {
            background-color: #FEFEFE;
            color: #000000;
            padding: 1rem;
            padding-top: 0rem;
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
        * {
            font-family: verdana, arial, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        #calobj{
            width: 100%; 
            border-collapse: collapse; 
            border-spacing: 0; 
            border: black 1px; 
            margin-bottom: 5px;
            table-layout: fixed;
        }
        #calobj th{
            width: 60%; 
            border: 1px solid #000;
        }
        #calobj td{
            border: 1px solid #000;
            padding-left: 1%;
            word-break:break-all; 
            word-wrap:break-word;
        }
       
        #content { 
            overflow:auto; 
            width: 100%; 
            /* background: gray;  */
        }
    </style>
    <title>Document</title>
</head>
<body>
    <header>        
        <div style="padding-top: 22px; padding-bottom: 1px;">
            <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/margin-bottom: 0px; " class="table table-bordered table-striped">
                <tbody class="table-striped">
                    <tr>
                        <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                            <div style="text-align: center; width: 230px; font-size: 11.5px; /*border: 1px solid #C00;*/">
                                <p style="margin: 1px;">FUERZA AÉREA BOLIVIANA</p>
                                <p style="margin: 1px;">DEPARTAMENTO I - PERSONAL EMGFAB</p>
                                <p style="margin: 1px;"><u><strong>BOLIVIA</strong></u></p>
                            </div>
                        </td>
                        <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                            <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                                <img style="/*border: 1px solid #C00;*/ width: 58; height: 58" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
                            </div>
                        </td>
                    </tr>  
                </tbody>
            </table>
        </div>
    </header> 
    <main>
        <div class="cabecera">
            <div style="padding-top: 5px; padding-bottom: 5px; text-align: center; font-size: 15px; font-weight: bold; /*border: 1px solid #C00;*/">
                <p style="margin: 1px">REPORTE DE FALTAS DISCIPLINARIAS DEL PERSONAL</p>
            </div> 
            <?php
                $date1 = date_create($fecha_inicio);
                $fechaini = date_format($date1,"d/m/Y");
                $date2 = date_create($fecha_final);
                $fechafin = date_format($date2,"d/m/Y");
        
            ?>          
            <div style="padding-top: 5px; padding-bottom: 5px; margin-left: 2cm; text-align: center; font-size: 14px; font-weight: normal; /*border: 1px solid #C00;*/">
                    <p style="margin: 1px"><b>DEL:</b>&nbsp;&nbsp;&nbsp;&nbsp; {{$fechaini}} &nbsp;&nbsp;&nbsp;&nbsp;<b>AL:</b>&nbsp;&nbsp;&nbsp;&nbsp; {{$fechafin}}</p>
            </div>
            
            <table  id="calobj">
                <thead style="background-color: lightgray;">
                    <tr>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 12%; font-size: 10px;">SANCION</th>                        
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 15%; font-size: 10px;">ARTICULO</th>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 10%; font-size: 10px;">TIPO</th>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 5%; font-size: 10px;">DIAS</th><th style="text-align: center; border: 1px solid #000; height:20px; width: 13%; font-size: 10px;">SANCIONADOR</th>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 11%; font-size: 10px;">DOCUMENTO</th>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 5%; font-size: 10px;">NDOC</th>
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 7%; font-size: 10px;">FECHA</th>                        
                        <th style="text-align: center; border: 1px solid #000; height:20px; width: 22%; font-size: 10px;">OBSERVACION</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @php
                        $p = 0;
                    @endphp
                    @foreach ($data as $key => $item)
                        
                        @if ($p != $item->per_codigo)
                        <tr>
                            <td colspan="5"> <b>NOMBRE: {{$item->gabre}} {{$item->complemento}} {{$item->nombre}} {{$item->paterno}} {{$item->materno}}</b> </td>
                            <td colspan="4"> <b>CARNET MILITAR: {{$item->per_cm}}</b> </td>
                        </tr>
                        @php
                            $p = $item->per_codigo
                        @endphp
                        @endif
                        
                        <tr>
                            <?php
                                $date = date_create($item->fecha);
                                $fechaasc = date_format($date,"d/m/Y");
                        
                            ?>
                            
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 5px;                     font-size: 7pt;">{{$item->sancion}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 3px;                     font-size: 7pt;">{{$item->articulo}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 1px; text-align: center; font-size: 7pt;">{{$item->tipo}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 0px; text-align: center; font-size: 7pt;">{{$item->dias}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 5px;                     font-size: 7pt;">{{$item->sancionador}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 0px; text-align: center; font-size: 7pt;">{{$item->documento}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 0px; text-align: center; font-size: 7pt;">{{$item->ndoc}}</td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 0px; text-align: center; font-size: 7pt;"><?php echo $fechaasc; ?></td>
                            <td style="padding-top: 5px; padding-bottom: 5px; padding-left: 2px;                     font-size: 7pt;">{{$item->observacion}}</td>
                            
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding-top: 5px; padding-bottom: 5px; text-align: right; font-size: 14px; font-weight: normal; /*border: 1px solid #C00;*/">
                La Paz, 
                    <?php
                        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                        echo date('d')." de ".$meses[date('n')-1]." del ".date('Y');
                    ?>
            </div>
            <div style="margin-left: 1cm; page-break-inside: avoid;">
                <div style="width: 55%; padding-top: 50px; padding-bottom: 0px; text-align: center; /*font-size: 14px; font-weight: bold; border: 1px solid #C00;*/">
                    <p style="margin: 1px; font-size: 12px;">CAP. DIM. AMILCAR ESTRADA LOAYZA</p>
                    <p style="margin: 1px; font-size: 12px; font-weight: bold;">JEFE DE LA SECC. "D" INFORMATICA DPTO I - EMGFAB</p>
                </div>
                

                <div style="padding-top: 30px; padding-bottom: 0px; text-align: center; /*font-size: 14px; font-weight: bold; border: 1px solid #C00;*/">
                    <p style="margin: 1px; font-size: 14px; font-weight: bold;">Vo. Bo.</p>
                </div>

                <div style="padding-top: 90px; padding-bottom: 0px; text-align: center; /*font-size: 14px; font-weight: bold; border: 1px solid #C00;*/">
                    <p style="margin: 1px; font-size: 14px;">{{$jefe_dpto->gra_abreviatura}}{{$jefe_dpto->estu_abreviatura}} {{$jefe_dpto->per_nombre}} {{$jefe_dpto->per_paterno}} {{$jefe_dpto->per_materno}}</p>
                    <p style="margin: 1px; font-size: 14px; font-weight: bold;">JEFE DEL DEPARTAMENTO I - PERSONAL EMGFAB</p>
                </div>
            </div>
            
            

        </div>
    </main>

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

    <script type="text/php">
        if(isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(380, 585, "$PAGE_NUM - $PAGE_COUNT", $font, 9);
            ');
        }
    </script>
</body>
</html>