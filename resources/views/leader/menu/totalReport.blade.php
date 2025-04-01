<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Encuestas Cerradas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Tabla de reporte con filtros y boton de descarga de los datos filtrados">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .tittle-close {
            color: #388E3C;
            margin: 0;
        }

        body {
            padding: 10px;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1F2937;
        }

        .container {
            max-width: auto;
            margin: 0 auto;
            padding: 16px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 16px;
            color: #1F2937;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        form label {
            font-weight: bold;
            margin-right: 8px;
        }

        form select,
        form input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 50px;
            max-width: 200;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            margin-top: 16px
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #388E3C;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            border: 2px solid #388E3C;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
        }



        .btn:hover {
            background-color: #45a049;
        }

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

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #388E3C;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #388E3C;
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

        .Search {
            background-color: #fff;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .Search form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
        }

        .Search form>div {
            flex: 1 1 100%;
            display: flex;
            gap: 16px;
            flex-direction:
        }

        .Search form>div:nth-child(1) {
            justify-content: flex-start;
        }

        .Search form>div:nth-child(2) {
            justify-content: flex-start;
        }

        .Search form>div:nth-child(3) {
            justify-content: flex-end;
        }

        .Search form div>div {
            display: flex;
            flex-direction: row;
            flex: 1;
        }

        .Search form label {
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }

        .Search form input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 100px;
            max-width: 400px;
        }

        .Search form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 150px;
            max-width: 400PX;
        }

        .Search form button {
            padding: 10px 16px;
            background-color: #388E3C;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .Search form button:hover {
            background-color: #388E3C;
        }

        @media (max-width: 500px) {
            .Search form>div {
                flex-direction: column;
            }
        }

        .search-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-start;
        }

        .search-item {
            flex: 1 1 200px;
            min-width: 150px;
            max-width: 300px;
        }

        .averages-container .minmax-row {
            display: flex;
            gap: 16px;
        }

        .float-container {
            position: relative;
            min-width: 150px;
            max-width: 300px;
        }

        .float-container input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: transparent;
            font-size: 14px;
            outline: none;
        }

        .float-container label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            background-color: #fff;
            padding: 0 4px;
            pointer-events: none;
            transition: 0.2s ease all;
            font-size: 12px;
        }

        .float-container input:focus+label,
        .float-container input:not(:placeholder-shown)+label {
            top: 0;
            left: 8px;
            transform: translateY(-50%) scale(0.9);
            font-size: 12px;
            color: #666;
        }

        .float-containerdos {
            position: relative;
            min-width: 100px;
            max-width: 150px;
        }

        .float-containerdos input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .float-containerdos label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
        }

        .float-containerdos input:focus+label,
        .float-containerdos input:not(:placeholder-shown)+label {}

        .float-containert {
            position: relative;
            margin-bottom: 16px;
            min-width: 150px;
            max-width: 400px;
            width: 100%;
        }

        .float-containert select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            background: transparent;
            box-sizing: border-box;
            position: relative;
            z-index: 0;
        }

        .float-containert label {
            position: absolute;
            left: 12px;
            top: -8px;
            background-color: #fff;
            padding: 0 4px;
            font-size: 12px;
            color: #666;
            pointer-events: none;
            z-index: 1;
        }

        .float-container.small {
            min-width: 80px;
            max-width: 110px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background-color: #388E3C;
            color: #fff;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 2px solid #388E3C;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .btn.btn-blue {
            background-color: #0275d8;
            border-color: #025aa5;
        }

        .btn.btn-blue:hover {
            background-color: #025aa5;
        }

        /* modales */

        #openQuestionsContent ul li {
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px solid #ddd;
            white-space: normal;
            white-space: pre-wrap;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            width: 70%;
            max-width: 70%;
            height: 80vh;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }

        /* Encabezado del modal */
.modal-content h2 {
  margin-top: 0;
  font-size: 1.8em;
  color: #333333;
}




.modal-select-container label {
  margin-bottom: 8px;
  font-weight: bold;
  color: #444444;
}
        .modal-select-container {
            margin-bottom: 15px;
        }

        .modal-select {
            width: 100%;
  padding: 8px;
  border: 1px solid #cccccc;
  border-radius: 4px;
  font-size: 1em;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            border-top: 1px solid #eee;
            padding-top: 10px;
            background-color: #f9f9f9;
            color: #555555;
            border-radius: 4px;
            font-size: 1em;


        }

        .modal-footer {
            margin-top: 15px;
            text-align: right;
        }

        .btn-res {
            display: inline-block;
            width: 80%;
            padding: 5px 10px;
            background-color: #388E3C;
            color: #fff;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
    </style>


</head>



<body>

    @include('leader.menu.header')

    <div class="container">
        <div style="display: flex; justify-content: space-between; padding:4px">
            <h4 class="tittle-close">Encuestas Cerradas</h4>
            <div>
                <div style="display: inline-block">
                    <a href="{{ route('leadertotalreportpdf.all', [
                        'survey_identifier' => request('survey_identifier'),
                        'instructor_search' => request('instructor_search'),
                        'knowledge_network_id' => request('knowledge_network_id'),
                    ]) }}"
                        class="btn">
                        <i class="fa fa-download" style="margin-right: 4px;"></i>
                        PDFs</a>
                </div>
                <div style="display: inline-block">
                    <a href="{{ route('leaderdownloadExcel', [
                        'survey_identifier' => request('survey_identifier'),
                        'instructor_search' => request('instructor_search'),
                        'knowledge_network_id' => request('knowledge_network_id'),
                        'min_average' => request('min_average'),
                        'max_average' => request('max_average'),
                    ]) }}"
                        class="btn">
                        <i class="fa fa-download" style="margin-right: 4px;"></i>
                        Excel
                    </a>
                </div>
            </div>

        </div>

        <div class="Search">
            <form method="GET" action="{{ route('reportsClose') }}">
                <div class="search-wrapper">
                    <div class="search-item">
                        <div class="float-containert">
                            <select name="survey_identifier" id="survey_identifier">
                                <option value="">Todos</option>
                                @foreach ($surveyIdentifiers as $identifier)
                                    <option value="{{ $identifier }}"
                                        @if ($surveyIdentifier == $identifier) selected @endif>
                                        {{ $identifier }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="survey_identifier">Formulario</label>
                        </div>
                    </div>

                    <div class="search-item">
                        <div class="float-container" style="box">
                            <input type="text" name="instructor_search" id="instructor_search" placeholder=" "
                                value="{{ request('instructor_search') }}">
                            <label for="instructor_search">Instructor</label>
                        </div>
                    </div>

                    <div class="search-item">
                        <div class="float-container">
                            <input type="text" name="knowledge_network_id" id="knowledgeNetworkId" placeholder=" "
                                value="{{ request('knowledge_network_id') }}">
                            <label for="knowledgeNetworkId">Área de conocimiento</label>
                        </div>
                    </div>

                    <div class="search-item">
                        <div class="float-container">
                            <input type="number" step="0.01" min="0" max="5" id="min_average"
                                name="min_average" placeholder=" " value="{{ request('min_average') }}"
                                onblur="formatDecimal(this)"
                                oninput="if(parseFloat(this.value) > 5){ this.value = 5; }
                    if(this.value.indexOf('.') !== -1) {
                        let parts = this.value.split('.');
                        if(parts[1].length > 2){
                           this.value = parts[0] + '.' + parts[1].substring(0,2);
                        }
                    }">
                            <label for="min_average">prom. mín</label>
                        </div>
                    </div>

                    <div class="search-item">
                        <div class="float-container">
                            <input type="number" step="0.01" min="0" max="5" id="max_average"
                                name="max_average" placeholder=" " value="{{ request('max_average') }}"
                                onblur="formatDecimal(this)" onblur="formatDecimal(this)"
                                oninput="if(parseFloat(this.value) > 5){ this.value = 5; }
                    if(this.value.indexOf('.') !== -1) {
                        let parts = this.value.split('.');
                        if(parts[1].length > 2){
                           this.value = parts[0] + '.' + parts[1].substring(0,2);
                        }
                    }">
                            <label for="max_average">prom. máx</label>
                        </div>
                    </div>

                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" class="btn btn-blue"
                        onclick="window.location='{{ route('leaderreportsClose') }}'">
                        <i class="fa fa-undo" style="margin-right: 4px;"></i>
                        Borrar</button>
                    <button type="submit" class="btn">
                        <i class="fa fa-search" style="margin-right: 4px;"></i>
                        Filtrar</button>

                </div>
            </form>
        </div>


        <script>
            function formatDecimal(input) {
                if (input.value !== '') {
                    let num = parseFloat(input.value);
                    if (!isNaN(num)) {
                        input.value = num.toFixed(2);
                    }
                }
            }
        </script>

        <table>
            <thead>
                <tr>
                    <th>Encuesta</th>
                    <th>N° de Documento</th>
                    <th>Nombres y Apellidos</th>
                    <th>Calificación Promedio</th>
                    <th style="max-width: 80px">Total Respuestas</th>
                    <th style="width: 30px; ">respuestas abiertas</th>
                    <th style="width: 30px; ">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summaries as $summary)
                    <tr>
                        <td>{{ $summary->survey_identifier }}</td>
                        <td>{{ $summary->identity_document }}</td>
                        <td>{{ $summary->instructor_name }} {{ $summary->instructor_last_name }}</td>
                        <td>{{ number_format($summary->average_qualification, 2) }}</td>
                        <td>{{ $summary->total_responses }}</td>
                        <td style="text-align: center;">

                            <a href="javascript:void(0)" class="view-open-questions"
                                style="color: #388E3C; font-weight: bold; "
                                data-survey="{{ $summary->survey_identifier }}"
                                data-instructor="{{ $summary->instructor_id }}">
                                <i class="fa fa-eye" style="margin-right: 4px;color: #388E3C; font-weight: bold;"></i>
                                Ver
                            </a>


                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('leadertotalreport', [
                                'id' => $summary->instructor_id,
                                'survey_identifier' => $summary->survey_identifier,
                                'instructor_search' => request('instructor_search'),
                                'knowledge_network_id' => request('knowledge_network_id'),
                            ]) }}"
                                style="color: #388E3C; font-weight: bold; text-decoration: none;">
                                <i class="fa fa-download" style="margin-right: 4px;"></i>
                                PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div class="pagination-wrapper">
            <ul class="pagination">
                @if ($summaries->onFirstPage())
                    <li class="disabled"><span>Primera</span></li>

                    <li class="disabled"><span>Anterior</span></li>
                @else
                    <li><a href="{{ $summaries->url(1) }}">Primera</a></li>

                    <li><a href="{{ $summaries->previousPageUrl() }}">Anterior</a></li>
                @endif
                @php
                    $currentPage = $summaries->currentPage();
                    $lastPage = $summaries->lastPage();
                    $maxPages = 5;
                    $startPage = max(1, $currentPage - floor($maxPages / 2));
                    $endPage = $startPage + $maxPages - 1;
                    if ($endPage > $lastPage) {
                        $endPage = $lastPage;
                        $startPage = max(1, $endPage - $maxPages + 1);
                    }
                @endphp
                @if ($startPage > 1)
                    <li><a href="{{ $summaries->url(1) }}">1</a></li>
                    @if ($startPage > 2)
                        <li class="disabled"><span>...</span></li>
                    @endif
                @endif
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $summaries->url($page) }}">{{ $page }}</a></li>
                    @endif
                @endfor
                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <li class="disabled"><span>...</span></li>
                    @endif
                    <li><a href="{{ $summaries->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif
                @if ($summaries->hasMorePages())
                    <li><a href="{{ $summaries->nextPageUrl() }}">Siguiente</a></li>
                    <li><a href="{{ $summaries->url($lastPage) }}">Última ({{ $lastPage }})</a></li>
                @else
                    <li class="disabled"><span>Siguiente</span></li>
                    <li class="disabled"><span>Última ({{ $lastPage }})</span></li>
                @endif
            </ul>
        </div>
    </div>




    <div id="openQuestionsModal" class="modal">
        <div class="modal-content">
            <h2>Respuestas Abiertas</h2>
            <div style="width: 100%; display:flex; justify-content:flex-end">
                <a id="downloadPdf" href="#" target="_blank" class="btn" style="width: max-content">
                    <i class="fa fa-download"></i> PDF
                </a>

            </div>

            <div class="modal-select-container">
                <label for="questionSelect">Seleccione el tipo de respuesta:</label>
                <select id="questionSelect" class="modal-select">
                    <option value="21">OBSERVACIONES</option>
                    <option value="22">RECOMENDACIÓN O SUGERENCIAS</option>
                </select>
            </div>
            <div id="openQuestionsContent" class="modal-body">

            </div>
            <br>
            <div class="modal-footer">
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('survey_identifier').addEventListener('change', function() {
            var surveyIdentifier = this.value;
            var instructorSelect = document.getElementById('instructor_id');
            if (!instructorSelect) return;
            var url = "{{ route('leaderapi.instructors') }}" + "?survey_identifier=" + encodeURIComponent(
                surveyIdentifier);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentSurveyIdentifier = null;
        let currentInstructorId = null;

        function loadOpenQuestions(questionId) {
            $.ajax({
                url: '{{ route("open.questions") }}',
                type: 'GET',
                data: {
                    survey_identifier: currentSurveyIdentifier,
                    instructor_id: currentInstructorId,
                    question_id: questionId
                },
                success: function(response) {
                    let html = '';
                    if (response.length > 0) {
                        html += '<ul>';
                        $.each(response, function(index, question) {
                            html += '<li>' + question.response + '</li>';
                        });
                        html += '</ul>';
                    } else {
                        html = '<p>No hay respuestas disponibles para esta pregunta.</p>';
                    }
                    $('#openQuestionsContent').html(html);
                },
                error: function() {
                    alert('Ocurrió un error al cargar las respuestas abiertas.');
                }
            });
        }

        function updateDownloadPdfLinkAll() {
            var url = '{{ route("openQuestionsPdf") }}' +
                      '?survey_identifier=' + currentSurveyIdentifier +
                      '&instructor_id=' + currentInstructorId;
            $('#downloadPdf').attr('href', url);
        }

        $(document).ready(function() {
            $(document).on('click', '.view-open-questions', function() {
                currentSurveyIdentifier = $(this).data('survey');
                currentInstructorId = $(this).data('instructor');

                // Actualiza el enlace del PDF para descargar todas las respuestas
                updateDownloadPdfLinkAll();

                // Se establece un valor por defecto en el select (ejemplo: "21")
                if ($('#questionSelect').length) {
                    $('#questionSelect').val('21');
                }

                // Carga las respuestas según el valor por defecto del select
                loadOpenQuestions('21');

                // Muestra el modal
                $('#openQuestionsModal').css('display', 'flex');
            });

            // Cuando se cambia el select, solo se recargan las respuestas en el modal
            $(document).on('change', '#questionSelect', function() {
                var questionId = $(this).val();
                loadOpenQuestions(questionId);
            });

            // Cierra el modal al hacer clic fuera del contenido
            $(window).on('click', function(e) {
                if ($(e.target).is('#openQuestionsModal')) {
                    $('#openQuestionsModal').css('display', 'none');
                }
            });
        });
    </script>

</body>

</html>
