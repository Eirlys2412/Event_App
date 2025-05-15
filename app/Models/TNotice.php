<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TNotice extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
         'item_id',
          'item_code',
           'title',
           'url_view'];
}
