<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Co_Provider extends Model
{
    protected $table = 'co_providers';
    /**
     * Join with User Details
     */
    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
