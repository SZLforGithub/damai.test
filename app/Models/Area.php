<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $guarded = [];

    public $timestamps = false;

    public function cities()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function streets()
    {
        return $this->hasMany('App\Models\Street');
    }
}
