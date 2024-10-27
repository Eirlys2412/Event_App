<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    use HasFactory;

    protected $table = 'resource_types'; // Tên bảng trong CSDL

    protected $fillable = [
        'title',
        'code',
    ];

    // Nếu cần thiết, bạn có thể thêm các phương thức quan hệ ở đây
}
