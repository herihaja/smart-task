<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskAIService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAITest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_inference_returns_scoring()
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Finish project report',
            'description' => 'Summarize quarterly results',
        ]);

        // Mock TaskAIService
        $this->mock(TaskAIService::class, function ($mock) use ($task) {
            $mock->shouldReceive('inferScoring')
                ->with($task->title, $task->description)
                ->once()
                ->andReturn([
                    'urgency' => 'high',
                    'impact' => 'high',
                    'effort' => 'low',
                ]);
        });

        // Call inference endpoint
        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks/ai/infer-score', [
                'title' => $task->title,
                'description' => $task->description,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'urgency' => 'high',
                'impact' => 'high',
                'effort' => 'low',
            ]);

        $response->assertJsonStructure([
            'urgency',
            'impact',
            'effort',
            'score',
        ]);
    }

    public function test_ai_inference_failure_returns_error()
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Prepare presentation',
            'description' => 'Quarterly results summary',
        ]);

        // Mock TaskAIService to throw an exception
        $this->mock(TaskAIService::class, function ($mock) use ($task) {
            $mock->shouldReceive('inferScoring')
                ->with($task->title, $task->description)
                ->once()
                ->andThrow(new Exception('AI API failed'));
        });

        // Call the inference endpoint
        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks/ai/infer-score', [
                'title' => $task->title,
                'description' => $task->description,
            ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'AI API failed',
            ]);
    }

    public function test_guest_cannot_infer_ai_score()
    {
        $response = $this->postJson('/api/v0/tasks/ai/infer-score', [
            'title' => 'Finish report',
            'description' => 'Quarterly results summary',
        ]);

        $response->assertStatus(401);
    }

    public function test_ai_inference_requires_title()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks/ai/infer-score', [
                'description' => 'Some description',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
}
