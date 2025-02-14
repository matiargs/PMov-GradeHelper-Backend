<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'path',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'assessments')
            ->withPivot('due_date')
            ->withTimestamps()
            ->using(Assessment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
