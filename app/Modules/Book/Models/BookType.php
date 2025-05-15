<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookType extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'status'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
