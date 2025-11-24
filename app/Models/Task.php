<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'urgency',
        'impact',
        'score',
        'due_date',
        'completed',
        'user_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date'  => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
