@foreach ($instructor->courses as $course)
    @if ($course)
        @php
            $hasData = $instructor->answers->contains(function($answer) use ($course) {
                return $answer->course_id == $course->id;
            });
        @endphp
        @if ($hasData)
            <button style="margin-bottom: 8px; width: max-content;" class="btn">
                <a href="{{ route('reports.show', [
                        'courseId' => $course->id,
                        'instructorId' => $instructor->id,
                    ]) }}"
                   style="display: block; padding: 8px 16px; color: inherit; text-decoration: none;">
                    {{ $course->code }}
                </a>
            </button>
        @else
            <button disabled style="margin-bottom: 8px; width: max-content;" class="btn-disabled">
                <span style="display: block; padding: 8px 16px;">{{ $course->code }}</span>
            </button>
        @endif
    @endif
@endforeach
