<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id', 'member_id', 'name', 'email', 'gender', 'contact',
        'experience', 'dob', 'address', 'description', 'photo_url', 'resume_url'
    ];

    // Belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // One TeamMember has many requests
    public function requests()
    {
        return $this->hasMany(\App\Models\Request::class, 'team_member_id');
    }
}
