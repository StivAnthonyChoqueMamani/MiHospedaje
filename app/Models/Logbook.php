<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logbook extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bedroom_logbooks()
    {
        return $this->hasMany(BedroomLogbook::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
