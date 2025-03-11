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
      text-align: center;
      font-size: 12px;
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
    th, td {
      border: 1px solid #ccc;
      padding: 4px;
      text-align: left;
    }
    th {
      background-color: #4CAF50;
      color: #fff;
      font-weight: normal;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .footer {
      text-align: center;
      font-size: 10px;
      margin-top: 10px;
      border-top: 1px solid #ccc;
      padding-top: 4px;
    }
  </style>
</head>
<body>
  <header>
    <img src="../img/imagen.png" alt="Loggoo SENA">
    <div class="header-text">
      <h2>SERVICIO NACIONAL DE APRENDIZAJE SENA</h2>
      <p>CENTRO DE COMERCIO Y SERVICIOS - REGIONAL CAUCA</p>
    </div>
  </header>

  <h1>ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA - EJECUCION DE LA FORMACIÓN</h1>

  <div class="details">
    <p><strong>Encuesta:</strong> {{ $surveyIdentifier }}</p>
    <p><strong>Instructor:</strong> {{ $instructor }}</p>
    <p><strong>Fecha:</strong> {{ $fecha }}</p>
  </div>

  @php
    $groupSizes = [6, 4, 6, 4];
    $groupTitles = ['1.	INTEGRALIDAD DEL INSTRUCTOR', '2.	PLANEACION DEL PROCEDIMIENTO DE EJECUCION DE LA FORMACION', '3.	EJECUCION DE LA FORMACION PROFESIONAL', '4.	EVALUACION'];
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
    <h2 class="grupo">{{$groupTitles[$index] ?? 'Grupo ' . ($index + 1)}}</h2>
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
              @if($summary->question)
                {{ $summary->question->question }}
              @else
                Sin pregunta
              @endif
            </td>
            <td style="text-align:center">{{ $summary->average_qualification }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  <div class="footer">
    Generado por: LISA - Centro de Comercio y Servicios Regional Cauca
  </div>
</body>
</html>
