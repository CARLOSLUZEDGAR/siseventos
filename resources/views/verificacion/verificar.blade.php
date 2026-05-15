<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../img/dgae_logo.png"> 
  <title>SISCAR - DGAE</title>
</head>
<body>
    <div style="padding-top: 0px; padding-bottom: 1cm;">
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
                </tr>  
            </tbody>
        </table>
    </div>

    <div>
        <table width="80%" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto 5px auto; background-image: url('../img/dgae_agua.png');
              background-size: auto;
              background-repeat: no-repeat;
              background-position: center;" class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td colspan="2" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: center;">
                        <h2>DATOS LICENCIA</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="/*border: 1px solid #000;*/ padding: 1px; text-align: center; vertical-align: middle;">
                        <img style="width: 4cm; height: 4cm; border: 2px solid #142A98;" src="../img/personal/{{$personal->per_foto}}">
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">GRADO:</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        <span style="margin: 1px;">{{$personal->abreviatura}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right; vertical-align: top;">
                        <span style="margin: 1px; font-weight: bold;">APELLIDO(S):</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        <span style="margin: 1px;">{{$personal->per_paterno}} {{$personal->per_materno}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">NOMBRE(S):</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        <span style="margin: 1px;">{{$personal->per_nombre}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">NRO. LICENCIA:</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        <span style="margin: 1px;">{{$personal->per_ci}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">NACIONALIDAD:</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        <span style="margin: 1px;">{{$personal->nacionalidad}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">FECHA DE NAC.:</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        {{ \Carbon\Carbon::parse($personal->per_fecha_nacimiento)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">ESTADO:</span>
                    </td>
                    @if ($estado == "VIGENTE")
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left; color: green;">
                        <span style="margin: 1px;">{{$estado}}</span>
                    </td>
                    @else
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left; color: red;">
                        <span style="margin: 1px;">{{$estado}}</span>
                    </td>
                    @endif
                </tr> 
                <tr>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: right;">
                        <span style="margin: 1px; font-weight: bold;">VIGENCIA:</span>
                    </td>
                    <td width="50%" style="/*border: 1px solid #000;*/ padding: 1px; font-size: 12pt; text-align: left;">
                        {{ \Carbon\Carbon::parse($personal->fecha_expiracion)->format('d/m/Y') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>