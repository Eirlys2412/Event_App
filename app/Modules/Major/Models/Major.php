<?php

namespace App\Modules\Major\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $table = 'chuyennganhs'; // Tên bảng

    protected $fillable = [
        'title',    // Tên chuyên ngành
        'slug',     // Slug chuyên ngành
        'status',   // Trạng thái chuyên ngành
    ];

    /**
     * Trả về slug của chuyên ngành
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
