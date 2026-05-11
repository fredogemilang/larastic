<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'metable_type',
        'metable_id',
        'robots',
        'json_ld',
        'og_type',
        'twitter_card',
    ];

    protected $casts = [
        'json_ld' => 'array',
    ];

    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
