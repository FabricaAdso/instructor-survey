<tbody>
    @foreach ($instructors as $instructor)
        <tr>
            <td>{{ $instructor->user->identity_document }}</td>
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
