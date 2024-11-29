<script src="https://cdn.tailwindcss.com"></script>
<form action="{{ route('survey.submit', $survey->id) }}" method="POST"
    class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    @csrf

    <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg p-6">

        <!-- Escala de valoración -->
        <details class="mb-6">
            <summary class="font-semibold bg-gray-200 text-gray-800 py-2 px-4 rounded-md cursor-pointer">
                Escala de Valoración
            </summary>
            <div class="mt-2 text-sm text-gray-600">
                <ul>
                    <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong> </li>
                    <li><strong>2: Insatisfecho / En desacuerdo</strong></li>
                    <li><strong>3: Neutral / Ni de acuerdo ni desacuerdo</strong> </li>
                    <li><strong>4: Satisfecho / De acuerdo</strong> </li>
                    <li><strong>5: Muy satisfecho / Muy de acuerdo</strong></li>
                </ul>
            </div>
        </details>
        @if ($survey->questions->isEmpty())
            <p><strong>No hay preguntas disponibles para esta encuesta.</strong></p>
        @else
            @foreach ($survey->questions as $question)

            <div class="flex justify-start mb-4">
                <h2 class="text-xl font-semibold text-gray-800">hhijh</h2>
            </div>

            <div class="flex justify-start mb-4">
                <h2 class="text-base font-semibold text-gray-700">{{ $question->question }}</h2>
            </div>


                @switch($question->type)
                    @case('radio')
                        <table class="w-full text-sm text-left border-collapse border border-gray-300 rounded-lg">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700">
                                    <th class="border border-gray-300 p-2 text-center">Instructor</th>
                                    @foreach ($question->options as $option)
                                        <th class="border border-gray-300 p-2 text-center">{{ $option }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($instructors->isEmpty())
                                    <tr class="bg-gray-200 text-gray-700">
                                        <strong>No hay instructores para esta ficha.</strong>
                                    </tr>
                                @else
                                    @foreach ($instructors as $instructor)
                                        <tr>
                                            <td class="border border-gray-300 p-2">{{ $instructor->name }} {{ $instructor->last_name }}</td>
                                            @foreach ($question->options as $option)
                                                <td class="border border-gray-300 p-2 text-center">
                                                    <input type="radio"
                                                        name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                        id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                        class="h-5 w-5">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    @break

                    @case('text')
                    <table class="w-full text-sm text-left border-collapse border border-gray-300 rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="border border-gray-300 p-2 text-center">Instructores</th>
                                <th class="border border-gray-300 p-2 text-center">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($instructors->isEmpty())
                            <tr>
                                <strong>No hay instructores para realizar la observacion.</strong>
                            </tr>
                            @else
                            @foreach ($instructors as $instructor)
                            <tr>
                                <td class="border border-gray-300 p-2">{{ $instructor->name }} {{ $instructor->last_name }}</td>
                               
                                <td class="border border-gray-300 p-2 text-center">
                                <textarea class="w-full h-full"
                                        name="answers[{{ $instructor->id }}][{{ $question->id }}]" 
                                        id="question-{{ $question->id }}-instructor-{{ $instructor->id }}">
                               </textarea>
                                 </td>
                               
                            </tr>
                                
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    @break
                    @default
                @endswitch
            @endforeach
        @endif
        <!-- Botón de navegación -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">Enviar Encuesta</button>
        </div>
    </div>
</form>
