<?php



namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Namhoc extends Model
{
    use HasFactory;

    protected $table = 'nam_hoc';
    protected $fillable = [
        'nam_hoc', 
    ];

    
}

