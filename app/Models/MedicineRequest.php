<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineRequest extends Model
{
    protected $table = "medicine_requests";

    protected $fillable = [
        'customer_name',
        'customer_mobile',
        'medicine_name',
        'notes',
        'status',
    ];
}
