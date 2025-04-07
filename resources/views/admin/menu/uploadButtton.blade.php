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
