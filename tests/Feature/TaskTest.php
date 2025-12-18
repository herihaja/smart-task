<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'My first task',
            'description' => 'Important work',
            'urgency' => 'high',
            'impact' => 'medium',
            'effort' => 'low',
        ];

        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'urgency',
                    'impact',
                    'effort',
                    'score',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'My first task',
            'user_id' => $user->id,
        ]);
    }

    public function test_score_is_computed_on_store()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks', [
                'title' => 'Scored task',
                'urgency' => 'high',
                'impact' => 'high',
                'effort' => 'low',
            ]);

        $score = $response->json('data.score');

        $this->assertIsInt($score);
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertEquals(21, $score);
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks', [
                'urgency' => 'high',
                'impact' => 'medium',
                'effort' => 'low',
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_invalid_urgency_is_rejected()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/v0/tasks', [
                'title' => 'Invalid task',
                'urgency' => 'urgent', // invalid
                'impact' => 'medium',
                'effort' => 'low',
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['urgency']);
    }

    public function test_user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'urgency' => 'low',
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson("/api/v0/tasks/{$task->id}", [
                'title' => $task->title,
                'urgency' => 'high',
                'impact' => $task->impact,
                'effort' => $task->effort,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'urgency' => 'high',
        ]);
    }

    public function test_user_cannot_update_others_task()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $task = Task::factory()->for($owner)->create();

        $response = $this
            ->actingAs($intruder)
            ->putJson("/api/v0/tasks/{$task->id}", [
                'title' => 'Hacked',
                'urgency' => 'high',
                'impact' => 'high',
                'effort' => 'low',
            ]);

        $response->assertStatus(403);
    }

    public function test_user_sees_only_their_tasks()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->count(2)->for($user)->create();
        Task::factory()->count(3)->for($other)->create();

        $response = $this
            ->actingAs($user)
            ->getJson('/api/v0/tasks');

        $response->assertStatus(200);

        $this->assertCount(2, $response->json('data'));
    }
}
