<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas Abiertas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Respuestas Abiertas</h2>
    <table>
        <thead>
            <tr>
                <th>ID Pregunta</th>
                <th>Respuesta</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($openQuestions as $question)
                <tr>
                    <td>{{ $question->question_id }}</td>
                    <td>{{ $question->response }}</td>
                    <td>{{ $question->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
