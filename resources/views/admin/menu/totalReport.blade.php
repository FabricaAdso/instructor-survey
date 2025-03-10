<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Encuestas Cerradas</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* Estilos Globales */
    body {
        padding: 10px;
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f3f4f6;
      color: #1F2937;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 16px;
    }
    h1 {
      font-size: 2rem;
      margin-bottom: 16px;
      color: #1F2937;
    }
    /* Formulario de Filtros */
    form {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
      align-items: center;
    }
    form label {
      font-weight: bold;
      margin-right: 8px;
    }
    form select, form input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      min-width: 200px;
    }
    form button {
      padding: 10px 16px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background-color: #45a049;
    }
    /* Buscador simple */
    #searchInput {
      width: 100%;
      max-width: 400px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 16px;
    }
    /* Tabla */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 16px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #4CAF50;
      color: #fff;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #e0f7fa;
    }
    /* Botón de descarga */
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 16px;
      transition: background-color 0.3s ease;
    }
    .btn:hover {
      background-color: #45a049;
    }
    /* Paginación */
    .pagination {
      display: flex;
      justify-content: center;
      list-style: none;
      padding: 0;
      margin: 16px 0;
    }
    .pagination li {
      margin: 0 4px;
    }
    .pagination a, .pagination span {
      display: inline-block;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-decoration: none;
      color: #38a901;
      transition: background-color 0.3s ease;
    }
    .pagination a:hover {
      background-color: #38a901;
      color: white;
      border-color: #38a901;
    }
    .pagination .active span {
      background-color: #38a901;
      color: white;
      border-color: #38a901;
    }
    .pagination .disabled span {
      color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  @include('admin.menu.header')
  <div class="container">
    <h1>Reporte de Encuestas Cerradas</h1>
    <form method="GET" action="{{ route('reportsClose') }}">
      <div>
        <label for="survey_identifier">Cuestionario:</label>
        <select name="survey_identifier" id="survey_identifier">
          <option value="">Todos</option>
          @foreach ($surveyIdentifiers as $identifier)
            <option value="{{ $identifier }}" @if($surveyIdentifier == $identifier) selected @endif>
              {{ $identifier }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="instructor_search">Buscar instructor (nombre o documento):</label>
        <input type="text" name="instructor_search" id="instructor_search" value="{{ request('instructor_search') }}">
      </div>
      <button type="submit">Filtrar</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>Encuesta (survey_identifier)</th>
          <th>N° de Documento</th>
          <th>Nombres y Apellidos</th>
          <th>Curso</th>
          <th>Promedio</th>
          <th>Respuestas</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($summaries as $summary)
        <tr>
          <td>{{ $summary->survey_identifier }}</td>
          <td>
            @if ($summary->instructor && $summary->instructor->user)
              {{ $summary->instructor->user->identity_document }}
            @endif
          </td>
          <td>
            @if ($summary->instructor && $summary->instructor->user)
              {{ $summary->instructor->user->name }} {{ $summary->instructor->user->last_name }}
            @endif
          </td>
          <td>
            @if ($summary->course)
              {{ $summary->course->code }}
              @if ($summary->course->program)
                ({{ $summary->course->program->name }})
              @endif
            @endif
          </td>
          <td>{{ $summary->average_qualification }}</td>
          <td>{{ $summary->total_responses }}</td>
          <td>
            <a href="{{ route('totalreport', ['id' => $summary->id]) }}">Ver detalles</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $summaries->links() }}

    <a href="{{ route('totalreportpdf.all', [
      'survey_identifier' => request('survey_identifier'),
      'instructor_search' => request('instructor_search')
    ]) }}" class="btn">Descargar PDFs Individuales Filtrados</a>
  </div>


  <script>
    // Actualización dinámica del select de instructores
    document.getElementById('survey_identifier').addEventListener('change', function() {
      var surveyIdentifier = this.value;
      var instructorSelect = document.getElementById('instructor_id');
      if (!instructorSelect) return; // Si no existe, no hace nada.
      var url = "{{ route('api.instructors') }}" + "?survey_identifier=" + encodeURIComponent(surveyIdentifier);
      fetch(url)
          .then(response => response.json())
          .then(data => {
              instructorSelect.innerHTML = '<option value="">Todos</option>';
              data.forEach(function(inst) {
                  var option = document.createElement('option');
                  option.value = inst.id;
                  option.text = inst.name;
                  instructorSelect.appendChild(option);
              });
          })
          .catch(error => {
              console.error("Error al obtener los instructores:", error);
          });
    });
  </script>
</body>
</html>

