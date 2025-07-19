<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineAvailability extends Model
{
    protected $table="pharmacy_medicine_available";
    protected $fillable=[
        'pharmacy_id',
        'medicine_name',
        'quantity',
        'price',
    ];
     public $timestamps = false;
}
