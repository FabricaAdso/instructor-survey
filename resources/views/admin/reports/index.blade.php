<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Instructores</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* Estilos Globales */
    /* ======================
   Estilos Globales
========================= */
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #f3f4f6;
  color: #1F2937;
  padding: 10px;
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

/* ======================
   Botones Base
========================= */
.btn {
  display: inline-block;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease, opacity 0.3s ease;
  font-size: 1rem;
  text-align: center;
  width: auto;
  max-width: 200px;
}

.btn:hover {
  transform: translateY(-2px);
  opacity: 0.9;
}

/* Grupo de botones para evitar que se estiren */
.button-group {
  display: flex;
  gap: 16px;
  align-items: center;
  margin-bottom: 24px;
  justify-content: flex-start;
}

/* ======================
   Botón de Abrir/Cerrar Encuesta
========================= */
/* Usamos el mismo tono de verde pero diferenciamos con opacidad */
.btn-toggle.survey-closed {
  background-color: #4CAF50; /* Verde */
  color: #fff;
  opacity: 1;
}

.btn-toggle.survey-open {
  background-color: #4CAF50; /* Mismo verde, pero con menor opacidad para indicar estado "abierto" */
  color: #fff;
  opacity: 0.8;
}

/* ======================
   Botón de Cargue Masivo
========================= */
/* Color neutro para diferenciar, sin ser rojo o azul */
.btn-mass {
  background-color: #555;
  color: #fff;
}

/* ======================
   Botón de Cancelación (para modales u otras acciones)
========================= */
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

.cancel-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ======================
   Input de Búsqueda
========================= */
#searchInput {
  width: 100%;
  max-width: 400px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-bottom: 16px;
}

/* ======================
   Tabla y Paginación
========================= */
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

/* ======================
   Toasts (Notificaciones)
========================= */
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
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}

@keyframes fadeOut {
  from { opacity: 1; }
  to { opacity: 0; }
}

/* ======================
   Modal
========================= */
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

.modal-close:hover {
  color: #333;
}

/* ======================
   Formulario en Modal
========================= */
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

  </style>
</head>
<body>
  @include('admin.menu.header')
  <div class="container">
    <h1>Reporte de Instructores</h1>
    <div class="button-group">
        <!-- Botón para abrir/cerrar la encuesta -->
        <button id="toggle-survey-status" class="btn btn-toggle {{ $isSurveyOpen ? 'survey-open' : 'survey-closed' }}">
          {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
        </button>
        <!-- Botón para abrir el modal de Cargue Masivo -->
        <button id="open-modal" class="btn btn-mass">Cargue Masivo</button>
      </div>


    <!-- Input de búsqueda -->
    <input type="text" id="searchInput" placeholder="Buscar...">

    <!-- Tabla de Reportes -->
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Reporte por Fichas</th>
          <th>Reporte General</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($instructors as $instructor)
        <tr>
          <td>{{ $instructor->user->name }} {{ $instructor->user->last_name }}</td>
          <td style="text-align: center;">
            <button onclick="openInstructorModal({{ $instructor->id }})" class="btn">
              Ver Fichas Asociadas
            </button>
          </td>
          <td style="text-align: center;">
            <button @if (!$instructor->hasGeneralAnswers) disabled @endif
              onclick="window.location.href='{{ $instructor->hasGeneralAnswers ? route('reportsGeneral', $instructor->id) : '#' }}'"
              class="btn"
              style="@if (!$instructor->hasGeneralAnswers) background-color: #D1D5DB; color: #fff; cursor: not-allowed; @endif">
              Reporte General
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Paginación  -->
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

    <!-- Modales para Fichas de cada Instructor -->
    @foreach ($instructors as $instructor)
    <div id="modal-{{ $instructor->id }}" class="modal">
      <div class="modal-content">
        <h2>Fichas Asociadas a {{ $instructor->user->name }} {{ $instructor->user->last_name }}</h2>
        <div style="margin-bottom: 16px;">
          @foreach ($instructor->courses as $course)
            @if ($course->program)
              <button style="margin-bottom: 8px; width: 100%;" class="btn">
                <a href="{{ route('reports.show', ['courseId' => $course->id, 'instructorId' => $instructor->id, 'programId' => $course->program->id]) }}"
                   style="display: block; padding: 8px 16px; color: #fff; text-decoration: none;">
                  {{ $course->code }}
                </a>
              </button>
            @else
              <button disabled style="margin-bottom: 8px; width: 100%;" class="cancel-button">
                <a style="display: block; padding: 8px 16px; color: #fff; text-decoration: none; cursor: not-allowed;">
                  {{ $course->code }}
                </a>
              </button>
            @endif
          @endforeach
        </div>
        <button onclick="closeInstructorModal({{ $instructor->id }})" class="cancel-button" style="width: 100%;">Cerrar</button>
      </div>
    </div>
    @endforeach

    <!-- Modal de Cargue Masivo -->
    <div id="modal" class="modal">
      <div class="modal-content">
        <button id="close-modal" class="modal-close">&times;</button>
        <h2>Subir Archivo Excel</h2>
        <form id="upload-form" action="{{ route('import-apprentices') }}" method="POST" enctype="multipart/form-data" class="modal-form">
          @csrf
          <label for="excel_file">Selecciona el archivo (.xlsx, .xls):</label>
          <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls">
          <button type="submit" class="btn">Subir</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Funciones de Modal para el Cargue Masivo
    const openModalBtn = document.getElementById('open-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const modal = document.getElementById('modal');

    openModalBtn.addEventListener('click', function() {
      modal.classList.add('show');
    });
    closeModalBtn.addEventListener('click', function() {
      modal.classList.remove('show');
    });
    window.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.classList.remove('show');
      }
    });

    // Funciones para abrir y cerrar modales individuales de cada instructor
    function openInstructorModal(id) {
      document.getElementById('modal-' + id).classList.add('show');
    }
    function closeInstructorModal(id) {
      document.getElementById('modal-' + id).classList.remove('show');
    }

    // Buscador simple para la tabla
    document.getElementById('searchInput').addEventListener('keyup', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("tbody tr");
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(filter) > -1 ? "" : "none";
      });
    });

    // Función para mostrar notificaciones (Toast)
    function showToast(message, type) {
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Botón para abrir/cerrar encuesta
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButton = document.getElementById('toggle-survey-status');
      if (!toggleButton) return;
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      if (!csrfToken) {
        console.error('Error: No se encontró el token CSRF.');
        return;
      }
      toggleButton.addEventListener('click', function() {
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
            this.classList.remove('bg-green-500');
            this.classList.add('bg-red-500');
            showToast('Encuesta abierta', 'success');
          } else {
            this.textContent = 'Abrir Encuesta';
            this.classList.remove('bg-red-500');
            this.classList.add('bg-green-500');
            showToast('Encuesta cerrada', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error al actualizar la encuesta', 'error');
        });
      });
    });
  </script>
</body>
</html>
