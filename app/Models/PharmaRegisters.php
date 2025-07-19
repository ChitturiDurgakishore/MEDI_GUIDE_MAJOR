<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmaRegisters extends Model
{
    protected $table="pharmacies_users";
    protected $fillable=[
        'pharmacy_name',
        'owner_name',
        'email',
        'password',
        'phone',
        'address',
        'map_link',
        'is_verified',
        'latitude',
        'longitude',
    ];
    public $timestamps=false;
}
