<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    /** @use HasFactory<\Database\Factories\TechnologyFactory> */
    use HasFactory;

    protected $table = 'technologies';

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'proficiency_level',
        'logo_path',
        'color_code',
        'display_order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_technology', 'technology_id', 'project_id');
    }
}
