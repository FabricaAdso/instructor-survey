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
                            <button type="button"
                                onclick="window.open('{{ route('reportsGeneral', $instructor->id) }}', '_blank')"
                                class="general-btn general-btn-data">
                                General
                            </button>
                        @else
                            <button onclick="showToast('No hay datos para el reporte general', 'error')" disabled
                                class="general-btn general-btn-nodata">
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
            @if (!$instructors->onFirstPage())
                <li><a href="#" onclick="performSearch(1)">Primera</a></li>
                <li><a href="#" onclick="performSearch({{ $currentPage - 1 }})">Anterior</a></li>
            @else
                <li class="disabled"><span>Primera</span></li>
                <li class="disabled"><span>Anterior</span></li>
            @endif

            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li><a href="#" onclick="performSearch({{ $page }})">{{ $page }}</a></li>
                @endif
            @endfor

            @if ($instructors->hasMorePages())
                <li><a href="#" onclick="performSearch({{ $currentPage + 1 }})">Siguiente</a></li>
                <li><a href="#" onclick="performSearch({{ $lastPage }})">Última ({{ $lastPage }})</a>
                </li>
            @else
                <li class="disabled"><span>Siguiente</span></li>
                <li class="disabled"><span>Última ({{ $lastPage }})</span></li>
            @endif
        </ul>



        {{-- <div class="jump-page-container">
            <label for="jumpPageInput">Ir a la página:</label>
            <input type="number" id="jumpPageInput" min="1" max="{{ $lastPage }}" class="jump-page-input">
            <button onclick="jumpToPage({{ $lastPage }})" class="jump-page-btn">Ir</button>
        </div> --}}
    </div>


</div>

<style>
    .last-page-badge {
        background-color: #4CAF50;
        /* Color de fondo */
        color: white;
        /* Color del texto */
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: bold;
    }
</style>

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


    .ficha-btn {
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



    .jump-page-container {
        text-align: center;
        margin-top: 10px;
    }

    .jump-page-container label {
        font-size: 1rem;
        color: #333;
        margin-right: 5px;
    }

    .jump-page-input {
        width: 60px;
        padding: 4px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }

    .jump-page-btn {
        padding: 6px 12px;
        margin-left: 5px;
        font-size: 1rem;
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .jump-page-btn:hover {
        background-color: #45a049;
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
        margin: 0 0 16px;
        color: #333;
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


</style>


<script>
    function jumpToPage(lastPage) {
        var input = document.getElementById('jumpPageInput');
        var page = parseInt(input.value);
        if (isNaN(page) || page < 1 || page > lastPage) {
            showToast("Por favor ingrese un número válido entre 1 y " + lastPage, "error");
        } else {
            performSearch(page);
        }
    }
</script>
