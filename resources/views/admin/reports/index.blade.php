<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Instructores</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
  margin: 0; /* Quita todos los márgenes */
  font-size: 1rem;
  margin-bottom: 10px; /* O agrega el margen que desees */
  color: #1F2937;
}


    /* Botones base */
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
    /* Toggle de encuesta: invertido según lo solicitado */
    .btn-toggle.survey-closed {
  background-color: #4CAF50; /* Verde para abrir encuesta */
  color: #fff;
  opacity: 1;
}

.btn-toggle.survey-open {
  background-color: #FF9800; /* Naranja suave para cerrar encuesta */
  color: #fff;
  opacity: 1;
}

    /* Botón de Cargue Masivo actualizado */
    .btn-mass {
      background-color: #00897B;
      color: #fff;
      border: 2px solid #00796B;
    }
    .btn-disabled {
      padding: 5px 5px;
      background-color: #D1D5DB;
      color: #333;
      font-size: 1.1rem;
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

    /* Estilos de tabla */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 16px;
    }
    th, td {
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
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #e0f7fa; }
    .pagination {
      display: flex;
      justify-content: center;
      list-style: none;
      padding: 0;
      margin: 16px 0;
    }
    .pagination li { margin: 0 4px; }
    .pagination a, .pagination span {
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

    /* Toast */
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
    .toast-success { background-color: #38a901; }
    .toast-error { background-color: #e53e3e; }
    @keyframes slideIn {
      from { transform: translateX(100%); }
      to { transform: translateX(0); }
    }
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }

    /* Estilos de modal */
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
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      position: relative;
    }
    .modal-content h2 {
      font-size: 1.5rem;
      margin-bottom: 16px;
      color: #333;
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
    .modal-close:hover { color: #333; }
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
    .modal-form button { align-self: flex-end; }

    /* Secciones específicas para evitar conflictos */
    .index-container .btn { }
    .table-container .btn { }
    {
      font-size: 0.9rem;
      padding: 8px 14px;
    }

    .modal-container .btn {
      font-size: 0.9rem;
      padding: 8px 14px;
      width: max-content

    }


    .btn-report-general, .btn-fichas {
      font-size: 0.9rem;
      width: max-content
    }

    /* Ajustar columnas de la tabla */
    th.document-number {
      width: 130px;
      text-align: center;
    }
    th.name {
      width: 50%;
    }

    .input-search{
  width: 100%;
  box-sizing: border-box;
    }



   /* Contenedor flex para la cabecera */
.header-flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap; /* Por defecto en una sola línea */
}

/* Estilos para cada sección */
.left-group,
.right-group {
  flex: 0 0 250px; /* Ancho fijo para las columnas laterales; ajusta según necesites */
}

.center-group {
  flex: 1;
  text-align: center;
}

/* Ajuste del input para que se adapte al ancho de su contenedor */
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
    flex: 0 0 100px; /* Ajusta este valor según necesites */
  }
}

/* Media query para cuando el ancho sea menor a 800px */
@media (max-width: 800px) {
  .header-flex {
    flex-wrap: wrap !important;
  }




  .left-group,
  .center-group,
  .right-group {
    flex: 1 0 100%;
    text-align: center;
    margin-bottom: 10px; /* Espacio entre filas */
  }
  /* Si no necesitas la columna derecha, la ocultas */
  .right-group {
    display: none;
  }
}


  </style>
</head>
<body>
  @include('admin.menu.header')
  <div class="container index-container">
    <h3>Reporte de Instructores</h3>
    <div class="header-flex">
        <!-- Columna izquierda con botones -->
        <div class="left-group">
          <div class="button-group">
            <!-- Toggle de Encuesta -->
            <button id="toggle-survey-status" class="btn btn-toggle {{ $isSurveyOpen ? 'survey-open' : 'survey-closed' }}">
              {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
            </button>
            <button id="open-modal" class="btn btn-mass">Cargue Masivo</button>
          </div>
        </div>

        <!-- Columna central con el formulario de búsqueda -->
        <div class="center-group">
          <form id="search-form" onsubmit="event.preventDefault(); performSearch();">
            <input class="input-search" placeholder="Nombre o documento" type="text" name="instructor_search" id="instructor_search" value="{{ request('instructor_search') }}">
            <button type="submit" class="btn" id="searchButton">Buscar</button>
          </form>
        </div>

        <!-- Columna derecha vacía (para balancear) -->
        <div class="right-group"></div>
      </div>


    <!-- Formulario de búsqueda -->

    <br>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th class="document-number">N# Documento</th>
            <th class="name">Nombre</th>
            <th class="report-course">Reporte Fichas</th>
            <th class="report-general">Reporte General</th>
          </tr>
        </thead>
        @include('admin.menu.tableIndex', ['instructors' => $instructors])
      </table>
      <!-- Paginación -->
      <div class="pagination-wrapper">
        <ul class="pagination">
          @if ($instructors->onFirstPage())
            <li class="disabled"><span>Anterior</span></li>
          @else
            <li><a href="{{ $instructors->previousPageUrl() }}">Anterior</a></li>
          @endif
          @php
            $currentPage = $instructors->currentPage();
            $lastPage = $instructors->lastPage();
            $maxPages = 5;
            $startPage = max(1, $currentPage - floor($maxPages / 2));
            $endPage = $startPage + $maxPages - 1;
            if ($endPage > $lastPage) {
              $endPage = $lastPage;
              $startPage = max(1, $endPage - $maxPages + 1);
            }
          @endphp
          @if ($startPage > 1)
            <li><a href="{{ $instructors->url(1) }}">1</a></li>
            @if ($startPage > 2)
              <li class="disabled"><span>...</span></li>
            @endif
          @endif
          @for ($page = $startPage; $page <= $endPage; $page++)
            @if ($page == $currentPage)
              <li class="active"><span>{{ $page }}</span></li>
            @else
              <li><a href="{{ $instructors->url($page) }}">{{ $page }}</a></li>
            @endif
          @endfor
          @if ($endPage < $lastPage)
            @if ($endPage < $lastPage - 1)
              <li class="disabled"><span>...</span></li>
            @endif
            <li><a href="{{ $instructors->url($lastPage) }}">{{ $lastPage }}</a></li>
          @endif
          @if ($instructors->hasMorePages())
            <li><a href="{{ $instructors->nextPageUrl() }}">Siguiente</a></li>
          @else
            <li class="disabled"><span>Siguiente</span></li>
          @endif
        </ul>
      </div>
    </div>
  </div>

  <!-- Modal para fichas asociadas -->
  <div class="modal-container">
    @foreach ($instructors as $instructor)
      <div id="modal-{{ $instructor->id }}" class="modal">
        <div class="modal-content">
          <h2>Fichas Asociadas a {{ $instructor->user->name }} {{ $instructor->user->last_name }}</h2>
          <div style="margin-bottom: 16px;">
            @include('admin.menu.modalCourses', ['instructor' => $instructor])
          </div>
          <button onclick="closeInstructorModal({{ $instructor->id }})" class="cancel-button" style="width: 100%;">Cerrar</button>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Modal de carga masiva -->
  <!-- Modal de carga masiva -->
<div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.6);">
    <div class="max-modal" style="position: relative; background-color: #fff; border-radius: 12px; padding: 30px; max-width: 480px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
      <!-- Botón de cierre superior (X) -->
      <button id="close-modal-top" style="position: absolute; top: 12px; right: 12px; background: transparent; border: none; font-size: 1.8rem; color: #aaa; cursor: pointer;">&times;</button>

      <h2 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">Subir Archivo Excel</h2>

      <form id="upload-form" action="{{ route('import-apprentices') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom: 20px;">
          <label for="file" style="display: block; font-size: 1rem; font-weight: 500; color: #555; margin-bottom: 8px;">Selecciona el archivo (.xlsx, .xls):</label>
          <input type="file" name="file" id="file" required style="display: block; width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
        </div>
        <!-- Fila inferior con dos botones en dirección row -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <!-- Botón inferior de cierre: X -->
          <button type="button" id="close-modal-bottom" style="padding: 6px 50px; background-color: #d00; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1.2rem;">
            &times;
          </button>
          <!-- Botón de Importar Excel -->
          <button type="submit" style="padding: 10px 14px; background-color: #38a901; color: #fff; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
            Importar Excel
          </button>
        </div>
      </form>
    </div>
  </div>



  <script>// Abrir modal
    // Abrir modal (asegúrate de tener un elemento con id "open-modal" en la vista)
  document.getElementById('open-modal').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'flex';
  });

  // Cerrar modal con el botón superior
  document.getElementById('close-modal-top').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'none';
  });

  // Cerrar modal con el botón inferior
  document.getElementById('close-modal-bottom').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'none';
  });

  // Cerrar modal si se hace clic fuera de la caja del modal
  window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('modal')) {
      document.getElementById('modal').style.display = 'none';
    }
  });
    // Modal de fichas asociadas
    function openInstructorModal(id) {
      document.getElementById('modal-' + id).classList.add('show');
    }
    function closeInstructorModal(id) {
      document.getElementById('modal-' + id).classList.remove('show');
    }

    // Toast
    function showToast(message, type) {
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(() => { toast.remove(); }, 3000);
    }

    // Toggle de encuesta
    document.addEventListener('DOMContentLoaded', () => {
      const toggleButton = document.getElementById('toggle-survey-status');
      if (!toggleButton) return;
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      if (!csrfToken) {
        console.error('Error: No se encontró el token CSRF.');
        return;
      }
      toggleButton.addEventListener('click', () => {
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
            toggleButton.textContent = 'Cerrar Encuesta';
            toggleButton.classList.remove('survey-closed');
            toggleButton.classList.add('survey-open');
            showToast('Encuesta abierta exitosamente', 'success');
          } else {
            toggleButton.textContent = 'Abrir Encuesta';
            toggleButton.classList.remove('survey-open');
            toggleButton.classList.add('survey-closed');
            showToast('Encuesta cerrada exitosamente', 'success');
          }
          // Actualiza la tabla con AJAX (se elimina la notificación de tabla actualizada)
          fetch('{{ route('admin.instructors') }}')
            .then(res => res.text())
            .then(html => {
              document.querySelector('table tbody').innerHTML = html;
            });
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error al actualizar la encuesta', 'error');
        });
      });
    });

    // Búsqueda AJAX
    function performSearch() {
      const searchValue = document.getElementById('instructor_search').value;
      const url = `{{ route('admin.instructors') }}?instructor_search=${encodeURIComponent(searchValue)}`;
      fetch(url)
        .then(response => response.text())
        .then(html => { document.querySelector('table tbody').innerHTML = html; })
        .catch(error => { console.error('Error en la búsqueda:', error); });
    }

    // Carga masiva
    document.getElementById('upload-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showToast(data.message || 'Archivo subido exitosamente', 'success');
        } else {
          showToast(data.error || 'Error al subir el archivo', 'error');
        }
        document.getElementById('modal').classList.remove('show');
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error al subir el archivo', 'error');
        document.getElementById('modal').classList.remove('show');
      });
    });
  </script>
</body>
</html>
