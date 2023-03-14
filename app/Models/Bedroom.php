<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bedroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function bedroom_logbooks()
    {
        return $this->hasMany(BedroomLogbook::class);
    }
}
