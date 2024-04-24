<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';

    /**
     * Join with Provider Details
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
