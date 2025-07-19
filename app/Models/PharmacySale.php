<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacySale extends Model
{
    use HasFactory;

    protected $table = 'pharmacies_sales'; // Specify your table name
    protected $guarded = ['id']; // Allow mass assignment for all except 'id'
    protected $casts = [
        'sold_at' => 'datetime', // Cast sold_at to Carbon instance
    ];
    protected $fillable = [
        'pharmacy_id',
        'medicine_name',
        'quantity_sold',
        'sold_at',
        'price_at_sale',
        'stock_before_sale',
        'day_of_week',
        'season',
        'weather_condition',
        'pharmacy_area',
        'customer_mobile',
    ];
    public $timestamps = false;
}
