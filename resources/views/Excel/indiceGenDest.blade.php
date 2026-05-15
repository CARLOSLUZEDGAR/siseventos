
    <table>
        <thead>
        <tr>
            <th>CM</th>
            <th>GRADO</th>
            <th>GRADO FIN</th>
            <th>APELLIDOS Y NOMBRES</th>
            <th>PATERNO</th>
            <th>MATERNO</th>
            <th>NOMBRES</th>
            <th>GENERO</th>
            <th>CI</th>
            <th>EXP</th>
            <th>ENTIDAD</th>
            <th>GRAN UNIDAD</th>
            <th>UNIDAD</th>
            <th>SECCION</th>
            <th>CARGO</th>
            <th>ESPECIALIDAD</th>
            <th>SUB ESPECIALIDAD</th>
            <th>FECHA_NAC</th>

        </tr>
        </thead>
        <tbody>
        @foreach($data as $invoice)
            <tr>
                <td>{{ $invoice->cm }} </td>
                <td>{{ $invoice->grado }} {{ $invoice->complemento }}</td>
                <td>{{ $invoice->grado }}</td>
                <td>{{ $invoice->paterno }} {{ $invoice->materno }} {{ $invoice->nombre }}</td>
                <td>{{ $invoice->paterno }} </td>
                <td>{{ $invoice->materno }}</td>
                <td>{{ $invoice->nombre }}</td>
                <td>{{ $invoice->genero }}</td>
                <td>{{ $invoice->ci }}</td>
                <td>{{ $invoice->exp }}</td>
                <td>{{ $invoice->dest1 }}</td>
                <td>{{ $invoice->dest2 }}</td>
                <td>{{ $invoice->dest3 }}</td>
                <td>{{ $invoice->dest4 }}</td>
                <td>{{ $invoice->cargo }}</td>
                <td>{{ $invoice->espe }}</td>
                <td>{{ $invoice->subesp }}</td>
                <td>{{ $invoice->fecha_nac }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>