<?php

namespace App\Modules\Community\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import đúng namespace của User

class JoinRequest extends Model
{
    protected $fillable = ['group_id', 'user_id', 'status'];

    public function group()
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Sửa namespace
    }
}