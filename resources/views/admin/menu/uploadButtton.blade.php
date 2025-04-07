<style>
       /* Modal */
       .modal {
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

        .modal.show {
            display: flex;
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

        /* Max Modal */
        .max-modal {
            position: relative;
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 600px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .max-modal h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        #close-modal-top {
            position: absolute;
            top: 12px;
            right: 12px;
            background: transparent;
            border: none;
            font-size: 2.8rem;
            color: #aaa;
            cursor: pointer;
        }

        /* Pestañas */
        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .tab-button.active {
            border-bottom: 3px solid #38a901;
            font-weight: bold;
        }

        /* Formulario de carga */
        .upload-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .upload-form .upload-label {
            display: block;
            font-size: 1.2rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .upload-form .upload-input {
            display: block;
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .upload-form .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-import {
            padding: 12px 14px;
            font-size: 1.1rem;
            background-color: #38a901;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        /* Modal de resultado */
        #result-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 1002;
        }

        .result-content {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        #result-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #result-message {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        #close-result-modal {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #38a901;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Modal de carga */
        #loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 1001;
        }

        .loading-content {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #38a901;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        #loading-modal p {
            margin-top: 10px;
            font-size: 1.1rem;
        }

        /* Hidden */
        .hidden {
            display: none !important;
        }
</style>

<!-- Modal de carga masiva -->
<div id="modal" class="modal">
    <div class="modal-content max-modal">
        <button id="close-modal-top" class="modal-close">&times;</button>
        <h2>Subir Archivo Excel</h2>

        <div class="tab-buttons">
            <button class="tab-button active" data-tab="usuarios-tab">Usuarios</button>
            <button class="tab-button" data-tab="lideres-tab">Líderes</button>
        </div>

        <div id="usuarios-tab" class="tab-content">
            <form id="upload-form-users" class="upload-form" action="{{ route('import-users') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <label for="file-users" class="upload-label">
                    Selecciona el archivo (.xlsx, .xls):
                </label>
                <input type="file" name="file" id="file-users" required class="upload-input">

                <div class="form-actions">
                    <button type="submit" class="btn-import">Importar Usuarios</button>
                </div>
            </form>
        </div>

        <div id="lideres-tab" class="tab-content hidden">
            <form id="upload-form-leaders" class="upload-form" action="{{ route('import-leaders') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <label for="file-leaders" class="upload-label">
                    Selecciona el archivo (.xlsx, .xls):
                </label>
                <input type="file" name="file" id="file-leaders" required class="upload-input">

                <div class="form-actions">
                    <button type="submit" class="btn-import">Importar Líderes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de resultado (éxito/error) -->
<div id="result-modal">
    <div class="result-content">
        <h2 id="result-title"></h2>
        <p id="result-message"></p>
        <button id="close-result-modal" class="btn">Cerrar</button>
    </div>
</div>

<!-- Modal de carga -->
<div id="loading-modal">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p>Cargando...</p>
    </div>
</div>

<script>
    // Funcionalidad de pestañas
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.style.borderBottom = 'none';
                btn.style.fontWeight = 'normal';
            });
            this.style.borderBottom = '3px solid #38a901';
            this.style.fontWeight = 'bold';

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(this.getAttribute('data-tab')).classList.remove('hidden');
        });
    });

    // Manejo del modal
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal');
        const openBtn = document.getElementById('open-modal');
        const closeBtn = document.getElementById('close-modal-top');

        openBtn.addEventListener('click', () => modal.classList.add('show'));
        closeBtn.addEventListener('click', () => modal.classList.remove('show'));
        window.addEventListener('click', e => e.target === modal && modal.classList.remove('show'));
    });

    // Configuración de formularios
    const setupUploadForm = (formId, route) => {
        document.getElementById(formId).addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const loadingModal = document.getElementById('loading-modal');
            const resultModal = document.getElementById('result-modal');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');

            loadingModal.style.display = 'flex';

            fetch(this.action, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                body: formData
            })
            .then(response => response.headers.get('content-type')?.includes('json') ? response.json() : response.blob())
            .then(data => {
                loadingModal.style.display = 'none';
                if (data instanceof Blob) {
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'errores.xlsx';
                    a.click();
                    window.URL.revokeObjectURL(url);
                    resultTitle.textContent = 'Advertencia';
                    resultMessage.textContent = 'Algunas filas tuvieron errores. Se descargó un archivo con detalles.';
                } else if (data?.success) {
                    resultTitle.textContent = 'Éxito';
                    resultMessage.textContent = data.message;
                    document.getElementById('modal').classList.remove('show');
                    fetch('{{ route('admin.instructors') }}')
                        .then(res => res.text())
                        .then(html => document.querySelector('.table-container').innerHTML = html);
                } else {
                    resultTitle.textContent = 'Error';
                    resultMessage.textContent = data?.error || 'Error al subir el archivo';
                }
                resultModal.style.display = 'flex';
            })
            .catch(error => {
                loadingModal.style.display = 'none';
                resultTitle.textContent = 'Error';
                resultMessage.textContent = 'Error en la conexión';
                resultModal.style.display = 'flex';
                console.error('Error:', error);
            });
        });
    };

    setupUploadForm('upload-form-users', 'import-users');
    setupUploadForm('upload-form-leaders', 'import-leaders');

    document.getElementById('close-result-modal').addEventListener('click', () => {
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
