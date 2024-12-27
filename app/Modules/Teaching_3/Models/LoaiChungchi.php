<?php
namespace app\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiChungchi extends Model
{
    protected $table = 'loai_chungchi';

    protected $fillable = ['title', 'status'];
}