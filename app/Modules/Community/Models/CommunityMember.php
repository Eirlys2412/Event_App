<?php

namespace App\Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CommunityMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'role',
        'status'
    ];

    // Quan hệ với người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với nhóm
    public function group()
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id', 'id');
    }

    public function role_info()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
} 