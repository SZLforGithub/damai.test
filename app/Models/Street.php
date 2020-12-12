<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $table = 'streets';

    protected $guarded = [];

    public $timestamps = false;

    public function areas()
    {
        return $this->belongsTo('App\Models\Area');
    }
}
