<?php
use Dompdf\Dompdf; //para incluir el namespace de la librería
 
 $dompdf = new Dompdf(); //crear el objeto de la clase Dompdf
?>

<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <title>{{$datos->documento}}</title>
    <style>
        html {
            margin-top: 3cm;
            margin-left: 3cm;
            margin-right: 3cm;
            margin-bottom: 1cm;
            
        }
        body {
        font-family: Arial, sans-serif; 
        font-size: 11.5px;
        }
        .b1{
            border-right: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        .b2{
            border-left: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        .marg{
            padding: 1px;
        }
        .tml{
            font-size: 12pt;
        }
    </style>
    <body>
        <header>
            <div style="text-align: center; width: 300px; font-size: 11px;">
                <p style="margin: 0px">FUERZA AÉREA BOLIVIANA</p>
                  <p style="margin: 0px 0px">{{$unidad->descripcion}}</p>
                  <p style="margin: 0px 0px;"><u><strong>BOLIVIA</strong></u></p>
            </div>
            <div style="padding-top: 30px; padding-bottom: 5px; text-align: center; font-size: 10px; font-weight: bold;">
                <p style="margin: 1px; font-size: 12pt;" >"AÑO DEL FORTALECIMIENTO DE LA SEGURIDAD OPERACIONAL Y DOCTRINA EN LA FAB."</p>
            </div>
            <div style="padding-top: 5px; padding-bottom: 10px; text-align: center; font-size: 14px; font-weight: bold;">
                <p style="margin: 1px; font-size: 14pt;" >MEMORÁNDUM</p>
            </div>
         </header>
        <section> 
            <div class="row">
                <table style="width: 100%;">
                    <tr>
                        <td class="b1" style="width: 50%;" valign="bottom" >
                            <p style="margin: 1px 0px 10px 0px; font-size: 12pt;"> <span style="text-transform: capitalize;">{{$departamento}},</span>  {{$fecha}}</p>
                        </td>
                        <td class="b2" style="width: 50%; padding-left: 10px;">
                            <p class="tml" style="margin: 0px"><b>Stria. {{$unidad->abreviatura}} N° {{$datos->ndoc}}</b></p>
                            <p class="tml" style="margin: 1px 0px;">Al Sr (a): {{$datos->gabre}} {{$datos->complemento}} </p>
                            <p class="tml" style="margin: 1px 0px;">{{$datos->nombre}} {{$datos->paterno}} {{$datos->materno}}</p>
                            <p class="tml" style="margin: 1px 0px;"><b>{{$datos->dest3}}</b></p>
                            <p class="tml" style="margin: 1px 0px 10px 0px; " align="right"><u>Presente.-</u></p>                                                       
                        </td>
                    </tr>
                </table>

            </div>
        </section>
        <br>
        <section>
            <p class="tml"  style="text-transform: capitalize;">Señor(a) {{$nomGra}}:</p>
            <p class="tml" style="text-align: justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Es usted sancionado(a) con {{$castigo}}, por infringir el Reglamento de Faltas Disiciplinarias
                y sus Castigos N° 23, por infringir el articulo {{$datos->f2}};
                {{$datos->observacion}}. Futuras faltas de esta naturaleza será sancionado de acuerdo a reglamento.
            </p>
        </section>
        <section>
            <div>
                <br>
                <p class="tml" style="text-indent: 1.5cm;">Le saluda a usted:</p>
                <p class="tml" style="text-align: center; margin: 0px"><b><i>"EL MAR NOS PERTENECE POR DERECHO,</i></b></p>
                <p class="tml" style="text-align: center; margin: 1px 0px;"><b><i>RECUPERARLO ES UN DEBER"</i></b></p>
            </div>
        </section>
        <section>
            <br>
            <br>
            <br>
            <br>
            <p class="tml" style="text-align: center; margin: 0px">{{$datos->sancionador}}</p>
            <p class="tml" style="text-align: center; margin: 1px 0px;"><b>{{$datos->cargo}}</b></p>
        </section>
       
        <footer>
            <div id="gracias">
                <p style="margin: 0px "><b>/{{$user}}</b></p>
                <p style="margin: 1px 0px;"><b>DISTRIBUCIÓN</b></p>
                <p style="margin: 1px 0px;">Original: Interesado</p>
                <p style="margin: 1px 0px;">Copia N° 1: Dpto I-EMGFAB</p>
                <p style="margin: 1px 0px;">Copia N° 2: Secretaria {{$unidad->abreviatura}}</p>
            </div>
        </footer>
    </body>
</html>