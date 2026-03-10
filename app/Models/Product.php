<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function getSizesAttribute($value)
    {
        // keep the old comma-separated sizes on the product for backwards compatibility
        return $value ? explode(',', trim($value, ',')) : [];
    }

    public function setSizesAttribute($value)
    {
        $this->attributes['sizes'] = is_array($value) ? implode(',', array_map('trim', $value)) : $value;
    }

    public function getColorsAttribute($value)
    {
        // keep the old comma-separated colors on the product for backwards compatibility
        return $value ? explode(',', trim($value, ',')) : [];
    }

    public function setColorsAttribute($value)
    {
        $this->attributes['colors'] = is_array($value) ? implode(',', array_map('trim', $value)) : $value;
    }

    /**
     * Variants allow independent stock by size/color combination.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
