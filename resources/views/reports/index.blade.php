<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reportes</title>
</head>

<body>
    <div class="container">
        <h1>Reportes de Encuestas</h1>
        <table>
            <thead>
                <tr>
                    <th>preguntas</th>
                    <th>respuestas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
                        <td>{{$question->question}}</td>
                        <td>
                            @foreach ($question->answers as $answer)
                            {{$answer->qualification}}
                                
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            {{-- @foreach ($answers as $answer)
            <tr>
                <td>
                    @if ($answer->questions && $answer->questions->isNotEmpty())
                        @foreach ($answer->questions as $question)
                            {{ $question->question }}<br>
                        @endforeach
                    @else
                        <em>Sin preguntas</em>
                    @endif
                </td>
                <td>{{ $answer->response ?? 'Sin respuesta' }}</td>
            </tr>
        @endforeach --}}

            </tbody>
        </table>

    </div>
</body>

</html>
