<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    // List tasks (ordered by smart score DESC)
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->orderByDesc('score')
            ->orderBy('due_date')
            ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        
        $data['user_id'] = $request->user()->id;

        $data['score'] = $this->computeScore(
            $data['urgency'],
            $data['impact'],
            $data['effort']
        );

        $task = Task::create($data);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validated();

        // Recompute only if one of the scoring fields changed
        if (
            isset($data['urgency']) || 
            isset($data['impact']) || 
            isset($data['effort'])
        ) {
            $urgency = $data['urgency'] ?? $task->urgency;
            $impact  = $data['impact']  ?? $task->impact;
            $effort  = $data['effort']  ?? $task->effort;

            $data['score'] = $this->computeScore($urgency, $impact, $effort);
        }

        $task->update($data);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

    private function computeScore($urgency, $impact, $effort)
    {
        $map = ['low' => 1, 'medium' => 2, 'high' => 3];

        return 
            ($map[$urgency] * 2) + 
            ($map[$impact] * 3) + 
            ((4 - $map[$effort]) * 2);
    }
}
