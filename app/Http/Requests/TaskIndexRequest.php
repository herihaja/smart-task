<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'       => ['sometimes', 'integer', 'min:1'],
            'per_page'   => ['sometimes', 'integer', 'min:1', 'max:200'],
            'search'     => ['sometimes', 'string', 'max:255'],

            'completed'  => ['sometimes', 'boolean', 'max:255'],
            'effort'     => ['sometimes', 'string', 'in:low,medium,high'],
            'urgency'    => ['sometimes', 'string', 'in:low,medium,high'],
            'impact'     => ['sometimes', 'string', 'in:low,medium,high'],
            'score'      => ['sometimes', 'integer'],
        ];
    }

    /**
     * Convenience: return sanitized filter array we care about.
     */
    public function filters(): array
    {
        return $this->only([
            'completed',
            'effort',
            'urgency',
            'impact',
            'score',
            'search',
        ]);
    }
}
