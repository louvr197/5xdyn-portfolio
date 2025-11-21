<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'long_description',
        'status',
        'context',
        'completion_date',
        'client',
        'duration_hours',
        'role',
        'github_link',
        'site_link',
        'display_order',
    ];

    protected $casts = [
        'completion_date' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'project_technology', 'project_id', 'technology_id');
    }

    public function techniques()
    {
        return $this->belongsToMany(Technique::class, 'project_technique', 'project_id', 'technique_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'project_id');
    }
}
