<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'contact_number',
        'address',
        'category_id',
        'work_shift_from',
        'work_shift_to',
        'start_date',
        'notes',
        'status',
        'team_member_id',
    ];

    // Belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to a Team Member
    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }

    // Belongs to a Category
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
