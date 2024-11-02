<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use hasFactory;

    protected $fillable = [
        'name',
        'city_id',
    ];
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function wards(){
        return $this->hasMany(Ward::class);
    }
}
