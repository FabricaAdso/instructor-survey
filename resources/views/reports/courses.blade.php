<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>fichas</title>
</head>
<body>
    <h1>Fichas</h1>
    <table>
        <thead>
            <tr>
                <th>Código de Ficha</th>
                <th>Instructores</th>
            </tr>
        </thead>
        <tbody> 
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->code }}</td>
                    <td>
                        @if ($course->instructors->isNotEmpty())
                            <ul>
                                @foreach ($course->instructors as $instructor)
                                    <li>{{ $instructor->first_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <em>Sin instructores</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



</body>

</html>
