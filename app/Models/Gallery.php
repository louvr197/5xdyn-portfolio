<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'project_id',
        'parent_id',
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'gallery_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function parent()
    {
        return $this->belongsTo(Gallery::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Gallery::class, 'parent_id');
    }
}
