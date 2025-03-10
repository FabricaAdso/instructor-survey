<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletín de Encuesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .datos {
            margin: 0 auto;
            max-width: 400px;
            font-size: 14px;
        }
        .datos p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Boletín de Encuesta</h2>
    </div>
    <div class="datos">
        <h2>Reporte del Instructor: {{ $instructor }}</h2>
    <p>Fecha: {{ $fecha }}</p>
    <p>Total de Respuestas: {{ $total_respuestas }}</p>
    </div>
</body>
</html>
