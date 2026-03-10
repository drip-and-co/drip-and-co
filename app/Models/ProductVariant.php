<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'SKU',
        'quantity',
        'stock_status',
        'image',
        'images',
    ];

    /**
     * Gallery image filenames as array (comma-separated in DB).
     */
    public function getGalleryArrayAttribute()
    {
        if (empty($this->images)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->images))));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
