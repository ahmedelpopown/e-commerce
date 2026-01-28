<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{

    use SoftDeletes;
    protected $fillable = [
        "category_id",
        "brand_id",
        "name",
        "slug",
        "sku",
        "short_description",
        "description",
        "price",
        "compare_price",
        "cost_price",
        "stock_quantity",
        "low_stock_threshold",
        "manage_stock",
        "stock_status",
        "is_active",
        "is_featured",
        "has_variants",
        "weight",
        "meta_title",
        "meta_description",
        "views_count",
    ];

    protected function casts()
    {
        return [
            "price" => "decimal:2",
            "compare_price" => "decimal:2",
            "weight" => 'decimal:2',
            "stock_quantity" => "integer",
            "low_stock_threshold" => "integer",
            "view_count" => "integer",
            "manage_stock" => "boolean",
            "is_active" => 'boolean',
            "is_featured" => "boolean",
            "has_variants" => "boolean",
        ];
    }


public function category(){
    return $this->belongsTo(Category::class);
}

public function brand(){
    return $this->belongsTo(Brand::class);
}

public function variants(){
    return $this->hasMany(ProductVariant::class);
}

public function images(){
    return $this->hasMany(ProductImage::class)->orderBy('sort_order');
}
public function primary_image(){
    return $this->hasOne(ProductImage::class)->where('is_primary',true);
}

public function reviews(){
    return $this->hasMany(Review::class);
}
public function approvedReviews(){
    return $this->hasMany(Review::class)->where("is_approved",true);
}







    #[Scope()]
    protected function active(Builder $query)
    {
        $query->where("is_active", true);

    }

    #[Scope()]
    protected function featured(Builder $query)
    {
        $query->where("is_featured", true);
    }

    #[Scope()]
    protected function inStock(Builder $query)
    {
        $query->where('stock_status', 'in_stock')
            ->where('stock_quantity', '>', 0);

    }

    #[Scope()]
    protected function lowStock(Builder $query)
    {
        $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0);

    }

    #[Scope()]
        protected function inCategory(Builder $query, int $categoryId)
    {
        $query->where("category_id", $categoryId);
    }

    #[Scope()]
    protected function ofBrand(Builder $query, int $brandId)
    {
        $query->where("brand_id", $brandId);
    }

    #[Scope()]
    protected function inPriceRange(Builder $query, float $min, float $max)
    {
        $query->whereBetween("price", [$min, $max]);
    }






    public function getDiscountPercentageAttribute(){
        if($this->compare_price && $this->compare_price > $this->price){
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }


    public function getAverageRatingAttribute(){
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute(){
        return $this->approvedReviews()->count();
    }

    public function incrementViews(){
        $this->increment("views_count");
    }




    protected static function boot(){
        parent::boot();
        static::creating(function($product){
        if(empty($product->slug)){
            $product->slug =Str::slug($product->name);
        }
        if(empty($product->sku)){
            $product->sku='SKU-' . strtoupper((Str::random(8)));
        }
        });

        static::updating(function ($product){
        if($product->isDirty('name')&& empty($product->slug)){
            $product->slug=Str::slug($product->name);
        }
        });
    }

}
