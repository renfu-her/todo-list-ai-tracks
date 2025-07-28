<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'user_id',
        'project_id',
        'collaborator_ids',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'priority' => 'string',
        'status' => 'string',
        'collaborator_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null, null, null, 'collaborator_ids')
            ->using(function ($collaboratorIds) {
                return User::whereIn('id', $collaboratorIds)->get();
            });
    }

    public function getCollaboratorsAttribute()
    {
        if (!$this->collaborator_ids) {
            return collect();
        }
        return User::whereIn('id', $this->collaborator_ids)->get();
    }
}
