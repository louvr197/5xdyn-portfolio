<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    /** @use HasFactory<\Database\Factories\InformationFactory> */
    use HasFactory;

    protected $table = 'information';

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'professional_title',
        'email',
        'phone',
        'location',
        'availability',
        'bio',
        'picture_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
