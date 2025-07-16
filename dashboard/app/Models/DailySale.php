<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'sale_date',
        'amount',
        'product_name',
        'quantity',
        'notes'
    ];
    
    protected $casts = [
        'sale_date' => 'date',
        'amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}