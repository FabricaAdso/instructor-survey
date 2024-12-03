
<script src="https://cdn.tailwindcss.com"></script>
<form action="{{ route('survey.submit', $survey->id) }}" method="POST">
    @csrf
    <h3>{{ $survey->name }}</h3>
    <p>{{ $survey->description }}</p>

    @if ($survey->questions->isEmpty())
        <p>No hay preguntas disponibles para esta encuesta.</p>
    @else
        @foreach ($survey->questions as $question)
            <div>
                <h4>{{ $question->question }}</h4>
                @if ($instructors->isEmpty())
                    <p>No hay instructores disponibles para esta encuesta.</p>
                @else
                    @foreach($instructors as $instructor)
                        <div class="flex w-full">
                            <p><strong>Instructor: {{ $instructor->first_name }} {{ $instructor->last_name }}</strong></p>
                            <div>
                                @if($question->type == 'radio')
                                @foreach ($question->options as $option)
                                        <input type="radio" name="answers[{{ $instructor->id }}][{{ $question->id }}]" value="{{ $option }}" id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}">
                                        <label for="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}">{{ $option }}</label>
                                @endforeach
                                @elseif($question->type == 'text')
                                    <textarea name="answers[{{ $instructor->id }}][{{ $question->id }}]" id="question-{{ $question->id }}-instructor-{{ $instructor->id }}"></textarea>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    @endif
    <button type="submit">Enviar Encuesta</button>
</form>
