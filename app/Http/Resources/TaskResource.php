<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'description'=> $this->description,
            'urgency'    => $this->urgency,
            'impact'     => $this->impact,
            'score'      => $this->score,
            'due_date'   => $this->due_date?->toISOString(),
            'completed'  => $this->completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
