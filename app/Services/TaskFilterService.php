<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TaskFilterService
{
    public function apply(User $user, array $filters): Builder
    {
        $query = Task::query()->where('user_id', $user->id);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['urgency'])) {
            $urgency = $filters['urgency'];
            $query->where('urgency', $urgency);
        }

        if (!empty($filters['effort'])) {
            $effort = $filters['effort'];
            $query->where('effort', $effort);
        }

        if (!empty($filters['impact'])) {
            $impact = $filters['impact'];
            $query->where('impact', $impact);
        }

        return $query;
    }
}
