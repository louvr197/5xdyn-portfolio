<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    /** @use HasFactory<\Database\Factories\TechniqueFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'proficiency_level',
        'display_order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_technique', 'technique_id', 'project_id');
    }
}
