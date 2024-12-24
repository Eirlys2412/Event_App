<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagBoDeTracNghiem extends Model
{
    //
    use HasFactory;

    // Định nghĩa bảng tương ứng
    protected $table = 'tag_bodetracnghiems';

    // Nếu bạn muốn cho phép mass assignment, hãy định nghĩa các thuộc tính này
    protected $fillable = [
        'tag_id',
        'bodetracnghiem_id',
    ];
}
