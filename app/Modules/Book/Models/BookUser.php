<?php

namespace App\Modules\Book\Models;

use App\Models\User; // Import User model
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points'];

    // Mối quan hệ giữa BookUser và User (N:1)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
