<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "sku",
        "is_in_stock",
        "quantity"
    ];

    protected $casts = [
        "is_in_stock" => "boolean",
    ];

    public function variants()
    {
        return $this->belongsToMany(Variant::class, "product_variants", "product_id", "variant_id");
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, "product_categories", "product_id", "category_id");
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
