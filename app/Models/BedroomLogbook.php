<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedroomLogbook extends Model
{
    use HasFactory;

    protected $table = 'bedroom_logbook';

    protected $guarded = [];

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }

    public function bedroom()
    {
        return $this->belongsTo(Bedroom::class);
    }

}
