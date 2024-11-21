<?php

namespace App\Modules\Book\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'photo', 'summary', 'content', 'tags', 'resources', 'status', 'user_id', 'book_type_id'];

    protected $casts = [
        'tags' => 'array',
        'resources' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookType(): BelongsTo
    {
        return $this->belongsTo(BookType::class);
    }
}
