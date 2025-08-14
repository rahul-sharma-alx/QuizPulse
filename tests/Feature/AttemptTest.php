<?php

namespace Tests\Feature;

use App\Models\Quizzes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_student_can_submit_attempt_and_score_calculated(){
        $user = User::factory()->create(['role'=>'student']);
        $quiz = Quizzes::factory()->create();
        // create questions & options factories...
        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/quizzes/{$quiz->id}/submit", [
                'answers' => [],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['attempt']);
    }
}
