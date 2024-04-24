<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    protected $table = 'webinars';
    protected $primaryKey = 'id';

    /**
     * Join with Provider Details
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
 