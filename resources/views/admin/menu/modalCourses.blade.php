@foreach ($instructor->courses as $course)
    @php
        $hasData = $instructor->answers->contains(function ($answer) use ($course) {
            return $answer->course_id == $course->id;
        });
        // Si el nombre está vacío, se mostrará "Curso sin nombre"
        $course->name = $course->code ?: 'Curso sin nombre';
    @endphp

    @if ($hasData)
        <button onclick="window.open('{{ route('reports.show', [$course->id, $instructor->id]) }}', '_blank')"
            class="course-btn course-btn-data">
            {{ $course->code }}
        </button>
    @else
        <button onclick="showToast('No hay datos de esta ficha', 'error')" class="course-btn course-btn-nodata" disabled>
            {{ $course->code }}
        </button>
    @endif
@endforeach

<style>
    /* Botón base para cursos */
    .course-btn {
        padding: 10px 20px;
        font-size: 1rem;
        margin: 5px;
        border-radius: 5px;
        border: 1px solid transparent;
        transition: background-color 0.3s ease;
    }

    /* Botón cuando hay datos: se ve activo y permite redirigir */
    .course-btn-data {
        background-color: #4CAF50;
        color: white;
        border-color: #4CAF50;
        cursor: pointer;
    }

    .course-btn-data:hover {
        background-color: #45a049;
    }

    /* Botón cuando no hay datos: deshabilitado y con mensaje al hacer clic */
    .course-btn-nodata {
        background-color: #cccccc;
        color: #333333;
        /* Cambié a un tono más oscuro para que se note */
        border: 1px solid #999999;
        cursor: not-allowed;
    }
</style>
