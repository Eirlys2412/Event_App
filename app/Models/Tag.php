<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Events\Models\Event;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','hit','status'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->title);
            }
        });
    }

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'tag_blogs');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tag_events');
    }
}
