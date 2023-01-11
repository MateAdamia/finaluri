<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quizes = [
            [
                'title' => "test1",
                "questions" => [
                    'question_1' => [
                        'correct' => '<op1>',
                        'title' => 'Question1?',
                        'option' => [
                            '<op1>',
                            '<op2>',
                            '<op3>',
                            '<op4>',
                        ]
                    ],
                    'question_2' => [
                        'correct' => 'op2',
                        'title' => 'Question2?',
                        'option' => [
                            'op1',
                            'op2',
                            'op3',
                            'op4',
                        ]
                    ],
         
                ]
            ],
            
        ];

        DB::table('quiz_quiz_question')->truncate();
        Quiz::truncate();
        QuizQuestion::truncate();
        QuizQuestionOption::truncate();
        QuizSubmission::truncate();
        QuizSubmissionUser::truncate();

        foreach ($quizes as $item) {
            $quiz = Quiz::create([
                'title' => $item['title'],
                'creator' => 1,
                'slug' => strtolower(str_replace(' ','-',$item['title'])),
            ]);

            $question_ids = [];
            foreach ($item['questions'] as $question) {
                $quiz_question = QuizQuestion::create([
                    // 'quiz_id' => $quiz->id,
                    'title' => $question['title'],
                    'creator' => 1,
                ]);
                $quiz_question->slug = rand(1000000000,9999999999).$quiz_question->id;
                $quiz_question->save();
                $question_ids[] = $quiz_question->id;

                foreach ($question['option'] as $option) {
                    $option = QuizQuestionOption::create([
                        // 'quiz_id' => $quiz->id,
                        'quiz_question_id' => $quiz_question->id,
                        'title' => $option,
                        'is_correct' => $question['correct'] == $option ? 1 : 0,
                    ]);
                    $option->slug = rand(1000000000,9999999999).$option->id;
                    $option->save();
                }
            }

            $quiz->related_quesions()->detach();
            $quiz->related_quesions()->attach($question_ids);
        }
    }
}
