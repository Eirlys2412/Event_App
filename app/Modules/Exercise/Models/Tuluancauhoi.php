<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import model User
use App\Modules\Teaching_2\Models\Module; // Import model Module

class Tuluancauhoi extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu khác với quy tắc đặt tên tự động
    protected $table = 'tulancauhoi';

    // Các cột có thể được gán hàng loạt
    protected $fillable = [
        'content',
        'hocphan_id',
        'user_id',
        'tags',
        'resources',
    ];

    // Nếu bạn muốn sử dụng JSON cho tags và resources
    protected $casts = [
        'tags' => 'array',
        'resources' => 'array',
    ];

    // Định nghĩa quan hệ với model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Khóa ngoại là user_id
    }

    // Định nghĩa quan hệ với model Module
    public function module()
    {
        return $this->belongsTo(Module::class, 'hocphan_id'); // Khóa ngoại là hocphan_id
    }
}