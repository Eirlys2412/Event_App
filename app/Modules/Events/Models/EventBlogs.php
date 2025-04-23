<?php

namespace App\Modules\Events\Models;

use App\Models\User;
use App\Modules\Blog\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventBlog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'blog_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
