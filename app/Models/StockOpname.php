<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'system_qty',
        'actual_qty',
        'difference',
        'note',
        'user_id',
        'opname_date',
    ];

    protected $casts = [
        'opname_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
