<tbody>
    @foreach ($instructors as $instructor)
        <tr>
            <td>{{ $instructor->user->identity_document }}</td>
            <td>{{ $instructor->user->name }} {{ $instructor->user->last_name }}</td>
            <td style="text-align: center;">
                @php
                    $hasAnyCourseData = $instructor->courses->contains(function($course) use ($instructor) {
                        return $course->program && $instructor->answers->contains(function($answer) use ($course) {
                            return $answer->course_id == $course->id;
                        });
                    });
                @endphp
                @if ($hasAnyCourseData)
                    <button onclick="openInstructorModal({{ $instructor->id }})" class="btn btn-fichas">
                        Ver Fichas Asociadas
                    </button>
                @else
                    <button disabled class="btn-disabled btn-fichas">
                        Ver Fichas Asociadas
                    </button>
                @endif
            </td>
            <td style="text-align: center;">
                @if ($instructor->hasGeneralAnswers)
                    <button onclick="window.location.href='{{ route('reportsGeneral', $instructor->id) }}'"
                            class="btn btn-report-general"
                            style="background-color: #4CAF50; color: white;">
                        Reporte General
                    </button>
                @else
                    <button disabled class="btn-disabled btn-report-general"
                            style="background-color: #cccccc; color: #666666;">
                        Reporte General
                    </button>
                @endif
            </td>



        </tr>
    @endforeach
</tbody>
