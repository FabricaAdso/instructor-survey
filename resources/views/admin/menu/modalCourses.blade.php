@php
    $sortedCourses = $instructor->courses->sortByDesc(function ($course) use ($instructor) {
        return $instructor->answers->contains(function ($answer) use ($course) {
            return $answer->course_id == $course->id;
        });
    });
@endphp

@foreach ($sortedCourses as $course)
    @php
        $hasData = $instructor->answers->contains(function ($answer) use ($course) {
            return $answer->course_id == $course->id;
        });
    @endphp

    @if ($hasData)
        <button style="margin-bottom: 8px; width: max-content;" class="btn">
            <a href="{{ route('reports.show', [
                'courseId' => $course->id,
                'instructorId' => $instructor->id,
            ]) }}"
                style="display: block; padding: 4px 8px; color: inherit; text-decoration: none;">
                {{ $course->code }}
            </a>
        </button>
    @else
        <button disabled style="margin-bottom: 8px; width: max-content;" class="btn-disabled">
            <span style="display: block; padding: 4px 8px;">
                {{ $course->code }}
            </span>
        </button>
    @endif
@endforeach
