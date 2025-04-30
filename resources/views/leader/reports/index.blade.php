<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Instructores</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

    <style>
        /* Estilos globales */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1F2937;
            padding: 10px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 16px;
        }

        h3 {
            margin: 0;
            font-size: 1rem;
            margin-bottom: 10px;
            color: #388E3C;
        }

        .btn {
            padding: 5px 5px;
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

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .btn-toggle.survey-closed {
            background-color: #4CAF50;
            color: #fff;
            opacity: 1;
        }

        .btn-toggle.survey-open {
            background-color: #FF9800;
            color: #fff;
            opacity: 1;
        }

        .btn-mass {
            background-color: #008934;
            color: #fff;
            border: 2px solid #007924;
        }

        .btn-disabled {
            background-color: #D1D5DB;
            color: #333;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 2px solid #D1D5DB;
            cursor: not-allowed;
            opacity: 1;
        }

        .cancel-button {
            display: block;
            width: 100%;
            margin-bottom: 8px;
            padding: 8px 16px;
            background-color: #ccc;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cancel-button:hover {
            background-color: #b3b3b3;
        }

        #instructor_search {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
            min-width: 80px;
            transition: border-color 0.3s;
        }

        #instructor_search:focus {
            border-color: #388E3C;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #388E3C;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
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
            color: #38a901;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #38a901;
            color: #fff;
            border-color: #38a901;
        }

        .pagination .active span {
            background-color: #38a901;
            color: #fff;
            border-color: #38a901;
        }

        .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
        }

        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: #fff;
            z-index: 1000;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-out 2.5s;
        }

        .toast-success {
            background-color: #38a901;
        }

        .toast-error {
            background-color: #e53e3e;
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

        .modal {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .modal.show {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            height: 300px;
        }

        .modal-content h4 {
            font-size: 0.9rem;
            margin-bottom: 16px;
            color: #333;
            margin-top: 0px
        }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-form label {
            font-weight: bold;
            color: #555;
        }

        .modal-form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        .modal-form button {
            align-self: flex-end;
        }

        .index-container .btn {}

        .table-container .btn {}

            {
            font-size: 0.9rem;
            padding: 8px 14px;
        }

        .modal-container .btn {
            font-size: 0.9rem;
            padding: 2px 4px;
            width: max-content
        }


        .btn-report-general,
        .btn-fichas {
            font-size: 0.9rem;
            width: max-content
        }

        th.document-number {
            width: 130px;
            text-align: center;
        }

        th.name {
            width: 50%;
        }

        .input-search {
            width: 100%;
            box-sizing: border-box;
        }

        .header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            /* Por defecto en una sola línea */
        }

        .left-group,
        .right-group {
            flex: 0 0 250px;
        }

        .center-group {
            flex: 1;
            text-align: center;
        }

        .input-search {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        @media (max-width: 920px) and (min-width: 801px) {
            .right-group {
                flex: 0 0 100px;
            }
        }

        @media (max-width: 800px) {
            .header-flex {
                flex-wrap: wrap !important;
            }

            .left-group,
            .center-group,
            .right-group {
                flex: 1 0 100%;
                text-align: center;
                margin-bottom: 10px;
            }

            .right-group {
                display: none;
            }
        }
    </style>
</head>

<body>
    @include('leader.menu.header')
    <div class="container index-container">
        <h3 >Reporte de Instructores - Area de {{  $user->areaLeader->knowledgeNetwork->name }}</h3>
        <div class="header-flex">


            <div class="center-group">
                <form onsubmit="event.preventDefault(); performSearch(1);">
                    <input type="text" id="instructor_search" name="instructor_search">
                    <button
                        style="font-size: 16px;  padding: 10px 20px;
                        font-size: 1rem;
                        margin: 5px;
                        border-radius: 5px;
                        border: 1px solid transparent;
                        transition: background-color 0.3s ease;
                        background-color: white;
                        color: #4CAF50;
                        border-color:#4CAF50;
                        cursor: pointer; "
                        type="submit">Buscar
                    </button>
                </form>

            </div>

            <div class="right-group"></div>
        </div>
        <br>
        <div class="table-container"></div>
    </div>



    <div id="modal"
     style="display: none;
     flex: 1;
     overflow-y: auto;
     position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.6);">

    <div class="max-modal"
         style="position: relative; background-color: #fff; border-radius: 12px; padding: 30px; max-width: 500px;width:600px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
        <button id="close-modal-top"
                style="position: absolute; top: 12px; right: 12px; background: transparent; border: none; font-size: 2.8rem; color: #aaa; cursor: pointer;">&times;</button>

        <h2 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">
            Subir Archivo Excel</h2>

        <!-- Pestañas -->
        <div style="display: flex; margin-bottom: 20px; border-bottom: 1px solid #ddd;">
            <button class="tab-button active" data-tab="usuarios-tab"
                    style="padding: 10px 20px; background: none; border: none; border-bottom: 3px solid #38a901; cursor: pointer; font-weight: bold;">
                Usuarios
            </button>
            <button class="tab-button" data-tab="lideres-tab"
                    style="padding: 10px 20px; background: none; border: none; cursor: pointer;">
                Líderes
            </button>
        </div>

        <!-- Contenido de pestañas -->
        <div id="usuarios-tab" class="tab-content" style="display: block;">
            <form style="display: flex; flex-direction: column; gap: 10px" id="upload-form-users" action="{{ route('import-users') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label for="file-users"
                           style="display: block; font-size: 1.2rem; font-weight: 500; color: #555; margin-bottom: 8px;">Selecciona
                        el archivo (.xlsx, .xls):</label>
                    <input type="file" name="file" id="file-users" required
                           style="display: block; width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button type="submit"
                            style="padding: 12px 14px; font-size: 1.1rem; background-color: #38a901; color: #fff; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                        Importar Usuarios
                    </button>
                </div>
            </form>
        </div>

        <div id="lideres-tab" class="tab-content" style="display: none;">
            <form style="display: flex; flex-direction: column; gap: 10px" id="upload-form-leaders" action="{{ route('import-leaders') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label for="file-leaders"
                           style="display: block; font-size: 1.2rem; font-weight: 500; color: #555; margin-bottom: 8px;">Selecciona
                        el archivo (.xlsx, .xls):</label>
                    <input type="file" name="file" id="file-leaders" required
                           style="display: block; width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button type="submit"
                            style="padding: 12px 14px; font-size: 1.1rem; background-color: #38a901; color: #fff; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                        Importar Líderes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Modal de Resultado (Éxito o Error) -->
    <div id="result-modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6); align-items: center; justify-content: center; z-index: 50;">
        <div
            style="background: #fff; padding: 20px; border-radius: 6px; text-align: center; max-width: 400px; width: 90%;">
            <h2 id="result-title" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;"></h2>
            <p id="result-message" style="font-size: 1.1rem; margin-bottom: 20px;"></p>
            <button id="close-result-modal"
                    style="padding: 10px 20px; font-size: 1rem; background-color: #38a901;
            color: #fff; border: none; border-radius: 6px; cursor: pointer;">Cerrar</button>
        </div>
    </div>


    <!-- Modal de Carga -->
    <div id="loading-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); align-items: center; justify-content: center; z-index: 1000;">
        <div
            style="background: #fff; padding: 20px; border-radius: 6px; text-align: center; max-width: 400px; width: 90%;">
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid #38a901; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;">

            </div>
            <p style="margin-top: 10px; font-size: 1.1rem;">Cargando...</p>
        </div>
    </div>

    <div id="survey-close-modal" class="survey-close-modal">
        <div class="survey-modal-content">
            <h2>Confirmar Cierre de Encuesta</h2>
            <p>¿Está seguro de que desea cerrar la encuesta? Esto consolidará los datos y no podrá reabrirla sin afectar
                la información.</p>
            <div class="survey-modal-buttons">
                <button id="confirm-close-survey" class="btn btn-confirm">Sí, cerrar</button>
                <button id="cancel-close-survey" class="btn btn-cancel">Cancelar</button>
            </div>
        </div>
    </div>


    <script>
        // Funcionalidad de pestañas
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remover clase active de todos los botones
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.style.borderBottom = 'none';
                    btn.style.fontWeight = 'normal';
                });

                // Agregar clase active al botón clickeado
                this.style.borderBottom = '3px solid #38a901';
                this.style.fontWeight = 'bold';

                // Ocultar todos los contenidos de pestañas
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });

                // Mostrar el contenido de la pestaña seleccionada
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).style.display = 'block';
            });
        });



        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        });

        function openInstructorModal(id) {
            document.getElementById('modal-' + id).classList.add('show');
        }

        function closeInstructorModal(id) {
            document.getElementById('modal-' + id).classList.remove('show');
        }


        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('toggle-survey-status');
            if (!toggleButton) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('Error: No se encontró el token CSRF.');
                return;
            }

            toggleButton.addEventListener('click', () => {
                if (toggleButton.classList.contains('survey-open')) {
                    const surveyModal = document.getElementById('survey-close-modal');
                    surveyModal.classList.add('show');
                } else {
                    toggleSurveyStatus(csrfToken.content);
                }
            });

            document.getElementById('confirm-close-survey').addEventListener('click', () => {
                toggleSurveyStatus(csrfToken.content);
                document.getElementById('survey-close-modal').classList.remove('show');
            });

            document.getElementById('cancel-close-survey').addEventListener('click', () => {
                document.getElementById('survey-close-modal').classList.remove('show');
            });
        });

        function toggleSurveyStatus(csrf) {
            const toggleButton = document.getElementById('toggle-survey-status');
            fetch('/admin/toggle-survey-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_survey_open) {
                        toggleButton.innerHTML =
                            '<i class="fas fa-sync-alt" style="font-size: 16px; transition: all 0.3s ease;"></i> Cerrar Encuesta';
                        toggleButton.classList.remove('survey-closed');
                        toggleButton.classList.add('survey-open');
                        showToast('Encuesta abierta exitosamente', 'success');
                    } else {
                        toggleButton.innerHTML =
                            '<i class="fas fa-sync-alt" style="font-size: 16px; transition: all 0.3s ease;"></i> Abrir Encuesta';
                        toggleButton.classList.remove('survey-open');
                        toggleButton.classList.add('survey-closed');
                        showToast('Encuesta cerrada exitosamente', 'success');
                    }
                    fetch('{{ route('leader.instructors') }}')
                        .then(res => res.text())
                        .then(html => {
                            document.querySelector('.table-container').innerHTML = html;
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error al actualizar la encuesta', 'error');
                });
        }


        function performSearch(page = 1) {
            const searchValue = document.getElementById('instructor_search').value;
            const url =
                `{{ route('leader.instructors') }}?page=${page}&instructor_search=${encodeURIComponent(searchValue)}`;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.table-container').innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', () => {
            performSearch();
        });


        // Configuración del formulario de usuarios
        document.getElementById('upload-form-users').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const loadingModal = document.getElementById('loading-modal');
            const resultModal = document.getElementById('result-modal');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');

            loadingModal.style.display = 'flex';

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.blob();
                    }
                })
                .then(data => {
                    loadingModal.style.display = 'none';

                    if (data instanceof Blob) {
                        const url = window.URL.createObjectURL(data);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'errores.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                        resultTitle.textContent = 'Advertencia';
                        resultMessage.textContent =
                            'Algunas filas no se importaron correctamente. Se ha descargado un archivo con los errores.';
                        resultModal.style.display = 'flex';
                    } else if (data && data.success) {
                        resultTitle.textContent = 'Éxito';
                        resultMessage.textContent = data.message || 'Archivo subido exitosamente';
                        resultModal.style.display = 'flex';
                        document.getElementById('modal').style.display = 'none';
                        fetch('{{ route('leader.instructors') }}')
                            .then(res => res.text())
                            .then(html => {
                                document.querySelector('.table-container').innerHTML = html;
                            })
                            .catch(error => console.error('Error al actualizar la tabla:', error));
                    } else {
                        resultTitle.textContent = 'Error';
                        resultMessage.textContent = data.error || 'Error al subir el archivo';
                        resultModal.style.display = 'flex';
                    }
                })
                .catch(error => {
                    loadingModal.style.display = 'none';
                    resultTitle.textContent = 'Error';
                    resultMessage.textContent = 'Error al subir el archivo. Inténtalo de nuevo.';
                    resultModal.style.display = 'flex';
                    console.error('Error:', error);
                });
        });

        // Configuración del formulario de líderes
        document.getElementById('upload-form-leaders').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const loadingModal = document.getElementById('loading-modal');
            const resultModal = document.getElementById('result-modal');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');

            loadingModal.style.display = 'flex';

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.blob();
                    }
                })
                .then(data => {
                    loadingModal.style.display = 'none';

                    if (data instanceof Blob) {
                        const url = window.URL.createObjectURL(data);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'errores.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                        resultTitle.textContent = 'Advertencia';
                        resultMessage.textContent =
                            'Algunas filas no se importaron correctamente. Se ha descargado un archivo con los errores.';
                        resultModal.style.display = 'flex';
                    } else if (data && data.success) {
                        resultTitle.textContent = 'Éxito';
                        resultMessage.textContent = data.message || 'Archivo subido exitosamente';
                        resultModal.style.display = 'flex';
                        document.getElementById('modal').style.display = 'none';
                        fetch('{{ route('leader.instructors') }}')
                            .then(res => res.text())
                            .then(html => {
                                document.querySelector('.table-container').innerHTML = html;
                            })
                            .catch(error => console.error('Error al actualizar la tabla:', error));
                    } else {
                        resultTitle.textContent = 'Error';
                        resultMessage.textContent = data.error || 'Error al subir el archivo';
                        resultModal.style.display = 'flex';
                    }
                })
                .catch(error => {
                    loadingModal.style.display = 'none';
                    resultTitle.textContent = 'Error';
                    resultMessage.textContent = 'Error al subir el archivo. Inténtalo de nuevo.';
                    resultModal.style.display = 'flex';
                    console.error('Error:', error);
                });
        });

        document.getElementById('close-result-modal').addEventListener('click', function() {
            document.getElementById('result-modal').style.display = 'none';
            window.location.reload();
        });

        function downloadErrorFile(blob) {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'errores.xlsx';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    </script>

    <style>
        .instructor-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .instructor-modal.show {
            display: flex;
            opacity: 1;
            pointer-events: auto;
        }



        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }



        .survey-close-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .survey-close-modal.show {
            display: flex;
            opacity: 1;
            pointer-events: auto;
        }

        .survey-modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .survey-modal-content h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .survey-modal-content p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #333;
        }

        .survey-modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-confirm {
            padding: 10px 20px;
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-confirm:hover {
            background-color: #d32f2f;
        }

        .btn-cancel {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-cancel:hover {
            background-color: #43a047;
        }
    </style>

</body>

</html>
