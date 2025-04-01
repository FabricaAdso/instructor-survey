<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Encuesta - {{ $surveyIdentifier }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            flex-direction: row;
            padding: 10px 0;
            border-bottom: 2px solid #4CAF50;
            margin-bottom: 10px;
        }

        header img {
            height: 60px;
            margin-right: 20px;
        }

        header .header-text {
            line-height: 1.2;
        }

        header .header-text h2 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        header .header-text p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #4CAF50;
            font-size: 14px;
        }

        .details {
            margin-bottom: 10px;
            font-size: 12px;
            text-align: left;
        }

        .details p {
            margin: 4px 0;
        }

        h2.grupo {
            margin-top: 20px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
            /* por defecto */
        }

        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: normal;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .centered {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            padding: 4px;
            border-top: 1px solid #ccc;
            background: white;
            height: 30px;
        }

        header::after {
  content: "";
  display: block;
  clear: both;
}

    </style>
</head>


<body>
    <header style="width: 100%; overflow: hidden; border-bottom: 2px solid #4CAF50; margin-bottom: 10px;">
        <img src="{{ public_path('img/logo-sena-verde-complementario-svg-2022.svg') }}"
             alt="Logo SENA"
             style="float: left; height: 60px; margin-right: 30px;">

        <div class="header-text" style="float: left; line-height: 1.2;">
          <h2 style="margin: 0; font-size: 16px; color: #333; margin-top:10px">
            SERVICIO NACIONAL DE APRENDIZAJE SENA
          </h2>
          <p style="margin: 0; font-size: 14px; color: #555;">
            CENTRO DE COMERCIO Y SERVICIOS - REGIONAL CAUCA
          </p>
        </div>
      </header>


    <h1>ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA - EJECUCION DE LA FORMACIÓN</h1>

    <div class="details">
        <p><strong>Encuesta:</strong> {{ $surveyIdentifier }}</p>
        <p><strong>Instructor:</strong> {{ $instructorName }} {{ $instructorLastName }}</p>
        <p><strong>N.° de documento:</strong> {{ $instructorIdentity }}</p>
    </div>

    @php
        $groupSizes = [6, 4, 6, 4];
        $groupTitles = [
            '1.	INTEGRALIDAD DEL INSTRUCTOR',
            '2.	PLANEACION DEL PROCEDIMIENTO DE EJECUCION DE LA FORMACION',
            '3.	EJECUCION DE LA FORMACION PROFESIONAL',
            '4.	EVALUACION',
        ];
        $groups = [];
        $start = 0;
        foreach ($groupSizes as $size) {
            if ($summaries->count() > $start) {
                $groups[] = $summaries->slice($start, $size);
            }
            $start += $size;
        }
    @endphp

    @foreach ($groups as $index => $group)
        <h2 class="grupo">{{ $groupTitles[$index] ?? 'Grupo ' . ($index + 1) }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:80%;">Item</th>
                    <th style="width:20%;">Calificación Promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($group as $summary)
                    <tr>
                        <td>
                            @if ($summary->question)
                                {{ $summary->question->question }}
                            @else
                                Sin pregunta
                            @endif
                        </td>
                        <td class="centered">
                            {{ $summary->average_qualification }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        <p>Generado el {{ date('d/m/Y') }}</p>
    </div>
</body>

</html>
