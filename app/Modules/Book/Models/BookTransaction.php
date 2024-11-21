<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'transaction_type',
        'points_change',
        'transaction_date',
    ];

    // Quan hệ với BookUser
    public function bookUser()
    {
        return $this->belongsTo(BookUser::class, 'user_id', 'user_id');
    }

    // Quan hệ với BookAccess
    public function bookAccess()
    {
        return $this->belongsTo(BookAccess::class, 'book_id', 'id');
    }
}
