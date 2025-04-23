<?php

namespace App\Modules\Community\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class CommunityGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'privacy',
        'status',
        'created_by',
        'slug',
        'cover_image',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class, 'group_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members', 'group_id', 'user_id')
                    ->withPivot('role', 'status', 'created_at', 'updated_at')
                    ->wherePivotIn('community_members.role', ['admin', 'moderator', 'member'])
                    ->wherePivot('community_members.status', 'active');
    }

    public function posts()
    {
        return $this->hasMany(CommunityPost::class, 'group_id');
    }

    public function scopeActiveUserGroups($query)
    {
        $userId = Auth::id();
        return $query->whereExists(function ($subQuery) use ($userId) {
            $subQuery->select(\DB::raw(1))
                     ->from('users')
                     ->join('community_members', 'users.id', '=', 'community_members.user_id')
                     ->whereColumn('community_groups.id', 'community_members.group_id')
                     ->where('community_members.user_id', $userId)
                     ->whereIn('community_members.role', ['admin', 'moderator', 'member'])
                     ->where('community_members.status', 'active');
        })->where('community_groups.status', 'active');
    }
}