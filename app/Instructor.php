<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructors';
    
    protected $primaryKey = 'id';

    /**
     * Join with Instructor Details
     */
    public function profile()
    { 
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

} 
