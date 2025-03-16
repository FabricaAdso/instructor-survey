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
                            $hasAnyCourseData = $instructor->courses->contains(function($course) use ($instructor) {
                                return $course->program &&
                                       $instructor->answers->contains(function($answer) use ($course) {
                                           return $answer->course_id == $course->id;
                                       });
                            });
                        @endphp
                        @if ($hasAnyCourseData)
                            <button onclick="openInstructorModal({{ $instructor->id }})"
                                    class="btn btn-fichas">
                                Fichas
                            </button>
                        @else
                            <button disabled class="btn-disabled btn-fichas">
                                Fichas
                            </button>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($instructor->hasGeneralAnswers)
                            <button onclick="window.location.href='{{ route('reportsGeneral', $instructor->id) }}'"
                                    class="btn btn-report-general"
                                    style="background-color: #4CAF50; color: white;">
                                General
                            </button>
                        @else
                            <button disabled class="btn-disabled btn-report-general"
                                    style="background-color: #cccccc; color: #666666;">
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
            $lastPage    = $instructors->lastPage();
            $maxPages    = 5;
            $startPage   = max(1, $currentPage - floor($maxPages / 2));
            $endPage     = $startPage + $maxPages - 1;

            if ($endPage > $lastPage) {
                $endPage   = $lastPage;
                $startPage = max(1, $endPage - $maxPages + 1);
            }
        @endphp

        <ul class="pagination">
            {{-- Botón "Anterior" --}}
            @if ($instructors->onFirstPage())
                <li class="disabled"><span>Anterior</span></li>
            @else
                <li>
                    <a href="#" onclick="performSearch({{ $currentPage - 1 }})">Anterior</a>
                </li>
            @endif

            {{-- Páginas intermedias --}}
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

            {{-- Botón "Siguiente" --}}
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

  <!-- Modal para fichas asociadas -->
  <div class="modal-container">
    @foreach ($instructors as $instructor)
        <div id="modal-{{ $instructor->id }}" class="modal">
            <div class="modal-content">
                <h4>Fichas Asociadas a {{ $instructor->user->name }} {{ $instructor->user->last_name }}</h4>
                <div style="width: 100%;
height: 120px;
overflow-y: auto;
min-height: 0;">
                    @include('admin.menu.modalCourses', ['instructor' => $instructor])
                </div>
                <button onclick="closeInstructorModal({{ $instructor->id }})" class="cancel-button"
                    style="width: 100%;">Cerrar</button>
            </div>
        </div>
    @endforeach
</div>

