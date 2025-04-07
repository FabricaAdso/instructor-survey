<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Instructores</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Global Styles */
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

        /* Botones */
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

        /* Input de búsqueda */
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

        /* Toasts */
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



        /* Header Flex */
        .header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
        }

        .left-group,
        .right-group {
            flex: 0 0 250px;
        }

        .center-group {
            flex: 1;
            text-align: center;
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

        /* Survey Modal */
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
            z-index: 2000; /* Mantenemos el más alto para confirmaciones críticas */
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

        /* Grupos y botones de la cabecera */
        .button-group {
            width: 320px;
        }

        #toggle-survey-status,
        #open-modal {
            font-size: 16px;
        }

        .btn-search {
            font-size: 1rem;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid transparent;
            transition: background-color 0.3s ease;
            background-color: white;
            color: #4CAF50;
            border-color: #4CAF50;
            cursor: pointer;
        }

    </style>
</head>

<body>
    @include('admin.menu.header')
    @include('admin.menu.uploadButtton')

    <div class="container index-container">
        <h3>Reporte de Instructores</h3>

        <div class="header-flex">
            <div class="left-group">
                <div class="button-group">
                    <button id="toggle-survey-status"
                        class="btn btn-toggle {{ $isSurveyOpen ? 'survey-open' : 'survey-closed' }}">
                        <i class="fas fa-sync-alt"></i>
                        {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
                    </button>

                    <button id="open-modal" class="btn btn-mass">
                        <i class="fa fa-upload" style="margin-right: 4px;"></i>
                        Cargue Masivo
                    </button>
                </div>
            </div>

            <div class="center-group">
                <form onsubmit="event.preventDefault(); performSearch(1);">
                    <input type="text" id="instructor_search" name="instructor_search">
                    <button type="submit" class="btn-search">Buscar</button>
                </form>
            </div>

            <div class="right-group"></div>
        </div>

        <br>

        <div class="table-container"></div>
    </div>

    <!-- Modal de confirmación de cierre de encuesta -->
    <div id="survey-close-modal" class="survey-close-modal">
        <div class="survey-modal-content">
            <h2>Confirmar Cierre de Encuesta</h2>
            <p>
                ¿Está seguro de que desea cerrar la encuesta? Esto consolidará los datos y no podrá
                reabrirla sin afectar la información.
            </p>
            <div class="survey-modal-buttons">
                <button id="confirm-close-survey" class="btn-confirm">Sí, cerrar</button>
                <button id="cancel-close-survey" class="btn-cancel">Cancelar</button>
            </div>
        </div>
    </div>
</body>


<script>

    // function openInstructorModal(id) {
    //     document.getElementById('modal-' + id).classList.add('show');
    // }

    // function closeInstructorModal(id) {
    //     document.getElementById('modal-' + id).classList.remove('show');
    // }

    // notificaciones emergentes temporales
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
                fetch('{{ route('admin.instructors') }}')
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
            `{{ route('admin.instructors') }}?page=${page}&instructor_search=${encodeURIComponent(searchValue)}`;

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

</script>
</body>
</html>
