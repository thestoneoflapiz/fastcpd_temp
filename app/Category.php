<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'courses';
    
    protected $primaryKey = 'id';

    public function courses()
    {
        return $this->belongsTo(Courses::class, 'id', 'profession_id', 'title', 'total_unit_amounts', 'price');
    }
}
