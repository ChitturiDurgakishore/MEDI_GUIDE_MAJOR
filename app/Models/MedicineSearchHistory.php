<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSearchHistory extends Model
{
    protected $table="medicine_search_history";
    protected $fillable=[
        'searched_medicine'
    ];
}
