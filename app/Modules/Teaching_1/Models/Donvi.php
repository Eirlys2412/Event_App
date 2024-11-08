<?php

namespace App\Modules\Teaching_1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donvi extends Model
{
    use HasFactory;

    protected $table = 'donvi';

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'children_id',
    ];

    protected $casts = [
        'children_id' => 'json',
    ];

    /**
     * Định nghĩa mối quan hệ "parent" với đơn vị cha.
     */
    public function parent()
    {
        return $this->belongsTo(Donvi::class, 'parent_id');
    }

    /**
     * Định nghĩa mối quan hệ "children" với các đơn vị con.
     */
    public function children()
    {
        return $this->hasMany(Donvi::class, 'parent_id');
    }
}
