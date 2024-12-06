<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Reportes</title>
    <style>
        /* Estilo para la tabla */
        #reportTable {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Encabezado de la tabla */
        #reportTable thead th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }

        /* Filas alternas */
        #reportTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        #reportTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Hover en filas */
        #reportTable tbody tr:hover {
            background-color: #e0f7fa;
        }

        /* Paginación */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 2px;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #45a049;
            color: #e0f7fa
        }

        /* Caja de búsqueda */
        .dataTables_wrapper .dataTables_filter input {
            padding: 6px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        /* Información de la tabla */
        .dataTables_wrapper .dataTables_info {
            margin-top: 10px;
            color: #666;
        }
    </style>
</head>

<body class="font-sans bg-gray-100">

    <header class="bg-white text-gray-800 border-b border-gray-300 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex justify-center">
                <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="w-12 h-12">
            </div>
            <h1 class="text-2xl font-semibold ml-4 flex-grow text-center md:text-left">Encuesta de Acompañamiento</h1>

            <div class="flex justify-end">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-green-500 hover:text-green-700">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container mx-auto mt-6 px-4">


        <!-- Modal -->
        <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Subir Archivo Excel</h2>
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="file" class="block font-medium text-gray-700">Selecciona el archivo
                            Excel</label>
                        <input type="file" name="file" id="file" required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500">
                    </div>
                    <button type="submit"
                        class="w-full py-2 px-4 bg-[#38a901] text-white font-medium rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Importar Excel
                    </button>
                </form>
                <button id="close-modal"
                    class="mt-4 w-full py-2 px-4 bg-gray-300 text-gray-800 rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancelar
                </button>
            </div>
        </div>

        <!-- Tabla de Reportes -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Reporte de Instructores</h2>
            <button id="open-modal"
                class="px-6 py-3 text-white bg-[#38a901] rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Cargue Masivo
            </button>
        </div>
        <table id="reportTable" class="display w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-200 text-gray-800">
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Reporte por Fichas</th>
                    <th class="px-4 py-2">Reporte General</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instructors as $instructor)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $instructor->name }}</td>
                        <td class="px-4 py-2 text-center">
                            <button onclick="openModal({{ $instructor->id }})"
                                class="px-4 py-2 bg-[#38a901] text-white rounded-lg hover:bg-[#38a980] focus:outline-none">
                                Ver Fichas Asociadas
                            </button>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button @if (!$instructor->hasGeneralAnswers) disabled @endif>
                                <a href="{{ $instructor->hasGeneralAnswers ? route('reports.general', $instructor->id) : '#' }}"
                                    class="px-4 py-2 rounded-lg focus:outline-none
                                @if ($instructor->hasGeneralAnswers) bg-[#38a901] text-white hover:bg-[#38a980]
                                @else bg-gray-400 text-white cursor-not-allowed @endif">
                                    Reporte General
                                </a>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modales para Fichas -->
        @foreach ($instructors as $instructor)
            <div id="modal-{{ $instructor->id }}"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Fichas Asociadas a {{ $instructor->name }}</h2>
                    <div class="space-y-2">
                        @foreach ($instructor->courses as $course)
                            @if ($course->hasAnswers)
                                <button>
                                    <a href="{{ route('reports.show', ['courseId' => $course->id, 'instructorId' => $instructor->id, 'programId' => $course->program->id]) }}"
                                        class="block px-4 py-2 bg-[#38a901] text-white rounded-lg hover:bg-green-700 focus:outline-none">
                                        {{ $course->code }}
                                    </a>
                                </button>
                            @else
                                <button disabled>
                                    <a class="block px-4 py-2 bg-gray-400 text-white rounded-lg focus:outline-none cursor-not-allowed">
                                        {{ $course->code }}
                                    </a>
                                </button>
                            @endif
                        @endforeach
                    </div>
                    <button onclick="closeModal({{ $instructor->id }})"
                        class="mt-4 w-full py-2 px-4 bg-gray-300 text-gray-800 rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cerrar
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        function openModal(id) {
            document.getElementById(`modal-${id}`).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(`modal-${id}`).style.display = 'none';
        }

        $(document).ready(function() {
            $('#reportTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                searchDelay: 200,
                initComplete: function(settings, json) {
                    const table = this.api();
                    $.fn.DataTable.ext.type.search.string = function(data) {
                        return !data ? '' : data.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                            .toLowerCase();
                    };
                    table.draw();
                }
            });
        });

        // Abre y cierra el modal de carga masiva
        document.getElementById('open-modal').addEventListener('click', function() {
            document.getElementById('modal').classList.remove('hidden');
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('modal').classList.add('hidden');
        });
    </script>

</body>

</html>
