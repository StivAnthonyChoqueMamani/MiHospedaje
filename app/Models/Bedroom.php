<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bedroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bedroom_logbooks()
    {
        return $this->hasMany(BedroomLogbook::class);
    }
}
