<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attempts;
use App\Models\Questions;
use App\Models\Quizzes;
use Illuminate\Http\Request;
use App\Http\Requests\SubmitAttemptRequest;
use App\Mail\SendResultEmail;
use DB;
use Cache;


class AttemptController extends Controller
{
    public function submit(SubmitAttemptRequest $req, Quizzes $quiz)
    {
        DB::beginTransaction();
        try {
            $user = $req->user();
            // calculate score
            $answers = $req->answers; // validated JSON array
            $score = 0;
            foreach ($answers as $ans) {
                $q = Questions::with('options')->find($ans['question_id']);
                // logic for single/multiple/text scoring
                // for single/multiple compare selected option ids with correct ones
                $correct = $q->options->where('is_correct', true)->pluck('id')->sort()->values();
                $selected = collect($ans['selected'] ?? [])->sort()->values();
                if ($correct->values()->toJson() == $selected->values()->toJson()) {
                    $score += $q->marks;
                }
            }
            $attempt = Attempts::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'answers' => json_encode($answers),
                'score' => $score,
                'started_at' => $req->started_at,
                'completed_at' => now()
            ]);
            DB::commit();

            // invalidate leaderboard cache for this quiz
            Cache::forget("leaderboard_quiz_{$quiz->id}");

            // dispatch async email / PDF job
            SendResultEmail::dispatch($attempt);

            return response()->json(['attempt' => $attempt], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
