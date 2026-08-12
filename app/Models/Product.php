<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'sku',
        'name',
        'purchase_price',
        'selling_price',
        'description',
        'image',
        'stock',
        'min_stock',
    ];

    protected $appends = ['stock_status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out';
        }

        if ($this->min_stock > 0 && $this->stock <= $this->min_stock) {
            return 'low';
        }

        return 'ok';
    }
}
