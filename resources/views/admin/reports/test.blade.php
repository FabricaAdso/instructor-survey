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

        ul {
            list-style: disc;
            margin-left: 20px;
        }

        li {
            margin-bottom: 4px;
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

        li {
            word-wrap: break-word;
            /* Soporte para navegadores antiguos */
            overflow-wrap: break-word;
            /* Soporte estándar */
            white-space: normal;
            /* Permite saltos de línea */
        }
    </style>
</head>

<body>
    <header style="width: 100%; overflow: hidden; border-bottom: 2px solid #4CAF50; margin-bottom: 10px;">
        <img src="{{ public_path('img/logo-sena-verde-complementario-svg-2022.svg') }}" alt="Logo SENA"
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

    <h1>REPORTE DE RESPUESTAS ABIERTAS - ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA - EJECUCION DE LA
        FORMACIÓN</h1>

    <div class="details">
        <p><strong>Encuesta:</strong> {{ $surveyIdentifier }}</p>
        @if ($instructor)
            <p><strong>Instructor:</strong> {{ $instructor->user->name }} {{ $instructor->user->last_name }}</p>
            <p><strong>N.° de documento:</strong> {{ $instructor->user->identity_document }}</p>
        @else
            <p><strong>Instructor:</strong> No definido</p>
        @endif
    </div>

    @if ($openQuestions->isEmpty())
        <p>No hay respuestas abiertas disponibles.</p>
    @else
        @foreach ($openQuestions as $questionId => $responses)
            <h2 class="grupo">
                {{-- Mostramos el texto de la pregunta; se asume que la relación 'question' existe --}}
                {{ $responses->first()->question->question ?? 'Pregunta no definida' }}
            </h2>
            <ul>
                @foreach ($responses as $response)
                    <li>{{ $response->response }}</li>
                @endforeach
            </ul>
        @endforeach
    @endif

    <div class="footer">
        <p>Generado el {{ date('d/m/Y') }}</p>
    </div>
</body>

</html>
