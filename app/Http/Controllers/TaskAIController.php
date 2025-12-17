<?php

namespace App\Http\Controllers;

use App\Services\TaskAIService;
use App\Services\TaskGlobalService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Request;

class TaskAIController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private TaskGlobalService $service) {}

    public function inferScore(Request $request, TaskAIService $ai)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $result = $ai->inferScoring(
            $data['title'],
            $data['description'] ?? null
        );

        // Validate AI output defensively
        foreach (['urgency', 'impact', 'effort'] as $field) {
            if (! in_array($result[$field], ['low', 'medium', 'high'], true)) {
                throw new \RuntimeException('Invalid AI response');
            }
        }

        $score = $this->service->computeScore(
            $result['urgency'],
            $result['impact'],
            $result['effort']
        );

        return response()->json([
            ...$result,
            'score' => $score,
        ]);
    }
}
