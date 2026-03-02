<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $value ? explode(',', trim($value, ',')) : [];
    }

    public function setSizesAttribute($value)
    {
        $this->attributes['sizes'] = is_array($value) ? implode(',', array_map('trim', $value)) : $value;
    }

    public function getColorsAttribute($value)
    {
        return $value ? explode(',', trim($value, ',')) : [];
    }

    public function setColorsAttribute($value)
    {
        $this->attributes['colors'] = is_array($value) ? implode(',', array_map('trim', $value)) : $value;
    }
}
