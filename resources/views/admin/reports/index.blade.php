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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reportes</title>
    <style>
        #reportTable {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        #reportTable thead th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }

        #reportTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        #reportTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #reportTable tbody tr:hover {
            background-color: #e0f7fa;
        }

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

        .dataTables_wrapper .dataTables_filter input {
            padding: 6px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 4px;
        }

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
                <form action="{{ route('logout.admin') }}" method="POST">
                    @csrf
                    <button id="logout-button" class="text-sm text-green-500 hover:text-green-700">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <script>
        document.getElementById('logout-button').addEventListener('click', function () {
            fetch("{{ route('logout.admin') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Redirige al login
                }
            })
            .catch(error => console.error("Error al cerrar sesión:", error));
        });
    </script>

    <div class="container mx-auto mt-6 px-4">

    <!-- Botón para abrir/cerrar la encuesta -->
    <button id="toggle-survey-status" class="text-white px-4 py-2 rounded {{ $isSurveyOpen ? 'bg-red-500' : 'bg-green-500' }}">
        {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
    </button>

    <style>
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            z-index: 1000;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-out 2.5s;
        }

        .toast-success {
            background-color: #38a901; /* Verde */
        }

        .toast-error {
            background-color: #e53e3e; /* Rojo */
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>

    <script>
        function showToast(message, type) {
            // Crear el contenedor de la notificación
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;

            // Agregar la notificación al cuerpo del documento
            document.body.appendChild(toast);

            // Eliminar la notificación después de 3 segundos
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }


        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggle-survey-status');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                console.error('Error: No se encontró el token CSRF.');
                return;
            }

            toggleButton.addEventListener('click', function () {
                fetch('/admin/toggle-survey-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_survey_open) {
                        this.textContent = 'Cerrar Encuesta';
                        this.classList.remove('bg-green-600');
                        this.classList.add('bg-red-600');
                        showToast('Encuesta abierta', 'success'); // Notificación de éxito
                    } else {
                        this.textContent = 'Abrir Encuesta';
                        this.classList.remove('bg-red-600');
                        this.classList.add('bg-green-600');
                        showToast('Encuesta cerrada', 'error'); // Notificación de error
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error al actualizar la encuesta', 'error'); // Notificación de error
                });
            });
        });
    </script>

    <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="relative bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg">
            <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">
                ✖
            </button>

            <div class="flex border-b mb-4">
                <button class="tab-button px-4 py-2 text-gray-600 hover:text-gray-800 active-tab" data-tab="aprendices">
                    Aprendices e Instructores
                </button>
            </div>

            <div id="aprendices" class="tab-content">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Subir Archivo Excel - Usuarios</h2>
                <form id="upload-form" action="{{ route('import-apprentices') }}" method="POST" enctype="multipart/form-data" class="space-y-4 flex items-center">
                    @csrf
                    <input type="file" name="file" id="file" accept=".xlsx, .xls"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <button type="submit"
                        class="ml-4 py-2 px-4 bg-[#38a901] text-white font-medium rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Cargar
                    </button>
                </form>

            </div>

            <!-- Modal de carga -->
        <div id="loading-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                <div class="flex items-center justify-center">
                    <div class="w-8 h-8 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="ml-2 text-gray-700">Cargando...</span>
                </div>
            </div>
        </div>

        <!-- Modal de resultado -->
        <div id="result-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                <h2 id="result-title" class="text-lg font-bold mb-4"></h2>
                <p id="result-message" class="text-sm text-gray-600 mb-4"></p>
                <button id="close-result-modal" class="w-full bg-green-500 text-white py-2 rounded">Cerrar</button>
            </div>
        </div>
        </div>


    </div>

    <script>
        document.getElementById('upload-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Evita el envío tradicional del formulario

            const formData = new FormData(this); // Obtiene los datos del formulario
            const loadingModal = document.getElementById('loading-modal'); // Modal de carga
            const resultModal = document.getElementById('result-modal'); // Modal de resultado
            const resultTitle = document.getElementById('result-title'); // Título del resultado
            const resultMessage = document.getElementById('result-message'); // Mensaje del resultado

            // Muestra el modal de carga
            loadingModal.classList.remove('hidden');

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    // Si la respuesta no es exitosa, lanza un error
                    throw new Error('Error en la respuesta del servidor');
                }

                // Verificar el tipo de contenido de la respuesta
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json(); // Manejar como JSON
                } else {
                    return response.blob(); // Manejar como archivo
                }
            })
            .then(data => {
                // Oculta el modal de carga
                loadingModal.classList.add('hidden');

                if (data instanceof Blob) {
                    // Si la respuesta es un archivo, descargarlo
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'errores.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);

                    // Mostrar un mensaje en el modal de resultado
                    resultTitle.textContent = 'Advertencia';
                    resultMessage.textContent = 'Algunas filas no se importaron correctamente. Se ha descargado un archivo con los errores.';
                    resultModal.classList.remove('hidden');
                } else if (data && data.success) {
                    // Si la respuesta es JSON y es exitosa, mostrar el mensaje de éxito
                    resultTitle.textContent = 'Éxito';
                    resultMessage.textContent = data.message;
                    resultModal.classList.remove('hidden');
                } else {
                    // Si la respuesta es JSON pero no es exitosa, mostrar el mensaje de error
                    resultTitle.textContent = 'Error';
                    resultMessage.textContent = data.error || 'Error al procesar el archivo. Inténtalo de nuevo.';
                    resultModal.classList.remove('hidden');
                }
            })
            .catch(error => {
                // Oculta el modal de carga y muestra el modal de resultado con un mensaje de error
                loadingModal.classList.add('hidden');
                resultTitle.textContent = 'Error';
                resultMessage.textContent = 'Error al procesar el archivo. Inténtalo de nuevo.';
                resultModal.classList.remove('hidden');
                console.error('Error:', error);
            });
        });

        // Cerrar el modal de resultado y recargar la página
        document.getElementById('close-result-modal').addEventListener('click', () => {
            document.getElementById('result-modal').classList.add('hidden');
            window.location.reload(); // Recarga la página
        });
    </script>

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
                    <td class="px-4 py-2">
                        {{ $instructor->user->name }} {{ $instructor->user->last_name }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button onclick="openModal({{ $instructor->id }})"
                            class="px-4 py-2 bg-[#38a901] text-white rounded-lg hover:bg-[#38a980] focus:outline-none">
                            Ver Fichas Asociadas
                        </button>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button
                            @if (!$instructor->hasGeneralAnswers) disabled @endif
                            onclick="window.location.href='{{ $instructor->hasGeneralAnswers ? route('reportsGeneral', $instructor->id) : '#' }}'"
                            class="px-4 py-2 bg-[#38a901] text-white rounded-lg hover:bg-[#38a980] focus:outline-none
                                @if (!$instructor->hasGeneralAnswers) bg-gray-400 text-white cursor-not-allowed @endif">
                            Reporte General
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modales para Fichas -->
        @foreach ($instructors as $instructor)
        <div id="modal-{{ $instructor->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Fichas Asociadas a {{ $instructor->name }} {{ $instructor->last_name}}</h2>

                <div class="space-y-2">
                    @foreach ($instructor->courses as $course)
                    @if ($course->hasAnswers && $course->program)                            <button>
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
            document.getElementById(`modal-${id}`).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(`modal-${id}`).classList.add('hidden');
        }

        $(document).ready(function () {
            $('#reportTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                searchDelay: 200,
                initComplete: function (settings, json) {
                    const table = this.api();
                    $.fn.DataTable.ext.type.search.string = function (data) {
                        return !data ? '' : data.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    };
                    table.draw();
                }
            });

            // Abre y cierra el modal de carga masiva
            document.getElementById('open-modal').addEventListener('click', function () {
                document.getElementById('modal').classList.remove('hidden');
            });

            document.getElementById('close-modal').addEventListener('click', function () {
                document.getElementById('modal').classList.add('hidden');
            });

            // Cambia entre pestañas Aprendices/Instructores
            const tabButtons = document.querySelectorAll(".tab-button");
            const tabContents = document.querySelectorAll(".tab-content");

            tabButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const tab = this.dataset.tab;

                    // Remueve la clase activa de todos los botones y oculta el contenido
                    tabButtons.forEach(btn => btn.classList.remove("active-tab"));
                    tabContents.forEach(content => content.classList.add("hidden"));

                    // Activa la pestaña seleccionada
                    this.classList.add("active-tab");
                    document.getElementById(tab).classList.remove("hidden");
                });
            });
        });
    </script>

    <style>
        .active-tab {
            border-bottom: 2px solid #38a901;
            font-weight: bold;
            color: #38a901;
        }

        @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Animación para el modal */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fixed {
    animation: fadeIn 0.3s ease-in-out;
}

/* Estilo para el botón de cerrar */
#close-result-modal {
    transition: background-color 0.3s ease;
}

#close-result-modal:hover {
    background-color: #38a901;
}
    </style>

</body>
</html>
