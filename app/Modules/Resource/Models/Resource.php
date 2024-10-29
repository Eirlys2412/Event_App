<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title', 'slug', 'file_name', 'file_type','file_size','description', 'path', 'url', 'type_code', 'link_code','resource_code'
    ];

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function resourceLinkType()
    {
        return $this->belongsTo(ResourceLinkType::class);
    }
}
