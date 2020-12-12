<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $guarded = [];
    
    public $timestamps = false;

    public function areas()
    {
        return $this->hasMany('App\Models\Area');
    }
}
