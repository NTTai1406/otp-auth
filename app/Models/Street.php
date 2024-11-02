<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ward_id',
    ];
    public function wards(){
        return this::beLongto(Ward::class);
    }
}
