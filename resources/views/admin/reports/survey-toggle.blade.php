<style>
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
    #toggle-survey-status,
        #open-modal {
            font-size: 16px;
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

</style>

<button id="toggle-survey-status"
                        class="btn btn-toggle {{ $isSurveyOpen ? 'survey-open' : 'survey-closed' }}">
                        <i class="fas fa-sync-alt"></i>
                        {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
                    </button>


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

<script>
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
</script>