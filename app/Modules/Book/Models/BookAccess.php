<?php

namespace App\Modules\Book\Models;

use App\Modules\Book\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookAccess extends Model
{
    use HasFactory;

    protected $table = 'book_access'; 

    protected $fillable = [
        'book_id',  
        'point_access', 
    ];

    // Mối quan hệ giữa BookAccess và Book (N:1)
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
