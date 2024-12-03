<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run()
    {
        $survey = Survey::create([
            'name' => 'ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA - EJECUCIÓN DE LA FORMACIÓN.',
            'description' => 'Esta encuesta tiene como objetivo evaluar la satisfacción de los aprendices con respecto a la ejecución de la formación en la etapa lectiva, con el fin de identificar áreas de oportunidad para mejorar la calidad del proceso educativo y optimizar las metodologías, recursos y estrategias empleadas, garantizando que las necesidades de los aprendices sean atendidas y que el aprendizaje sea efectivo y de calidad.',
        ]);

        // INTEGRALIDAD DEL INSTRUCTOR
        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Presentación personal',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Relaciones interpersonales',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Conocimiento general del área',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Actitud de servicio',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Lenguaje claro y sencillo',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Puntualidad',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);


        //PLANEACION DEL PROCEDIMIENTO DE EJECUCION DE LA FORMACION
        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Establece el plan de trabajo concertado',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Socializa el programa de formación',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Socializa el proyecto formativo',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Socializa las guías de aprendizaje',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);


        // EJECUCION DE LA FORMACION PROFESIONAL
        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Propone ejemplos o ejercicios que vinculan los resultados de aprendizaje con la práctica real',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Orienta de manera clara los conocimientos y procesos asociados con la competencia',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Propicia el desarrollo de un ambiente de respeto y confianza',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Estimula la reflexión sobre la manera que aprende',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Presenta y expone las sesiones de formación de manera organizada y estructurada',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Utiliza diversas estrategias, métodos y materiales',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);


        // EVALUACION
        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Identifica los conocimientos y habilidades de los aprendices al inicio de cada competencia',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Aplica técnicas e instrumentos de evaluación de acuerdo con la evidencia requerida',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Da a conocer los resultados de la evaluación en el plazo establecido',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'Retroalimenta con el aprendiz las valoraciones realizadas',
            'type' => 'radio',
            'options' => ['1', '2', '3', '4', '5'],
        ]);


        //OBSERVACIONES Y RECOMENDACIONES
        Question::create([
            'survey_id' => $survey->id,
            'question' => 'OBSERVACIONES.',
            'type' => 'text',
            'options' => null,
        ]);

        Question::create([
            'survey_id' => $survey->id,
            'question' => 'RECOMENDACIÓN O SUGERENCIAS:',
            'type' => 'text',
            'options' => null,
        ]);


    }
}



// Question::create([
//     'survey_id' => $survey->id,
//     'question' => 'Puntualidad',
//     'type' => 'radio',
//     'options' => ['Sí', 'No'],
// ]);



