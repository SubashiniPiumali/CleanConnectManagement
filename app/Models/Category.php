<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

     // One Category has many request
     public function requests()
     {
         return $this->hasMany(\App\Models\Request::class);
     }
 
     // One Category has many Team Members
     public function teamMembers()
     {
         return $this->hasMany(\App\Models\TeamMember::class);
     }
}
