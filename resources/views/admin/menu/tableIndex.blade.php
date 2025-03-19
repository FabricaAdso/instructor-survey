<div class="table-container-ajax">
    <table>
        <thead>
            <tr>
                <th class="document-number">N# Documento</th>
                <th class="name">Nombre</th>
                <th class="report-course">Reporte Fichas</th>
                <th class="report-general">Reporte General</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($instructors as $instructor)
                <tr>
                    <td>{{ $instructor->user->identity_document }}</td>
                    <td>{{ $instructor->user->name }} {{ $instructor->user->last_name }}</td>
                    <td style="text-align: center;">
                        @php
                            $hasAnyCourseData = $instructor->courses->contains(function ($course) use ($instructor) {
                                return $course->program &&
                                    $instructor->answers->contains(function ($answer) use ($course) {
                                        return $answer->course_id == $course->id;
                                    });
                            });
                        @endphp
                        <button onclick="openInstructorModal({{ $instructor->id }})" class="ficha-btn">
                            Fichas
                        </button>
                    </td>
                    <td style="text-align: center;">
                        @if ($instructor->answers->isNotEmpty())
                            <button onclick="window.location.href='{{ route('reportsGeneral', $instructor->id) }}'"
                                class="general-btn general-btn-data">
                                General
                            </button>
                        @else
                            <button onclick="showToast('No hay datos para el reporte general', 'error')"
                                disabled class="general-btn general-btn-nodata">
                                General
                            </button>
                        @endif
                    </td>


                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación para AJAX -->
    <div class="pagination-wrapper">
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

        <ul class="pagination">
            @if ($instructors->onFirstPage())
                <li class="disabled"><span>Anterior</span></li>
            @else
                <li>
                    <a href="#" onclick="performSearch({{ $currentPage - 1 }})">Anterior</a>
                </li>
            @endif

            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li>
                        <a href="#" onclick="performSearch({{ $page }})">
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endfor

            @if ($instructors->hasMorePages())
                <li>
                    <a href="#" onclick="performSearch({{ $currentPage + 1 }})">
                        Siguiente
                    </a>
                </li>
            @else
                <li class="disabled"><span>Siguiente</span></li>
            @endif
        </ul>
    </div>
</div>

<div class="modal-container">
    @foreach ($instructors as $instructor)
        <div id="modal-{{ $instructor->id }}" class="instructor-modal">
            <div class="modal-content">
                <h4>Fichas Asociadas a {{ $instructor->user->name }} {{ $instructor->user->last_name }}</h4>
                <div style="width: 100%; height: 220px; overflow-y: auto;">
                    @include('admin.menu.modalCourses', ['instructor' => $instructor])
                </div>
                <button onclick="closeInstructorModal({{ $instructor->id }})" class="cancel-button"
                    style="width: 100%;">Cerrar</button>
            </div>
        </div>
    @endforeach
</div>

<style>
    /* Botón base para el reporte general */
    .general-btn {
        padding: 10px 20px;
        font-size: 1rem;
        margin: 5px;
        border-radius: 5px;
        border: 1px solid transparent;
        transition: background-color 0.3s ease;
    }
    /* Botón activo (con respuestas) */
    .general-btn-data {
        background-color: #4CAF50;
        color: white;
        border-color: #4CAF50;
        cursor: pointer;
    }
    .general-btn-data:hover {
        background-color: #45a049;
    }
    /* Botón deshabilitado (sin respuestas) */
    .general-btn-nodata {
        background-color: #cccccc;
        color: #666666;
        border: 1px solid #999999;
        cursor: not-allowed;
    }


    .ficha-btn{
        padding: 10px 20px;
    font-size: 1rem;
    margin: 5px;
    border-radius: 5px;
    border: 1px solid transparent;
    transition: background-color 0.3s ease;
        background-color: #4CAF50;
    color: white;
    border-color: #4CAF50;
    cursor: pointer;
    }
</style>
