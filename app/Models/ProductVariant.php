<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
  protected $fillable=[
"product_id",
'sku',
"name",
"options",
"price",
"compare_price",
"stock_quantity",
"stock_status",
"is_active",
"sort_order",
  ];

  protected function casts(){
    return [
        "options"=>"array",
        "price"=>"decimal:2",
        "compare_price"=>"decimal:2",
        "stock_quantity"=>"integer",
        "is_active"=>"boolean",
        "sort_order"=>"integer",
    ];
  }


  protected function active (Builder $query){
    $query->where("is_active",true);
  }

  protected function inStock(Builder $query){
    $query->where('stock_status','in_stock')
    ->where("stock_quantity",">",0); 
  }


  public function product(){
    return $this->belongsTo(Product::class);
  }
  public function images(){
    return $this->hasMany(ProductImage::class);
  }
  public function orderItems(){
    return $this->hasMany(OrderItem::class);
  }

public function getDiscountPercentageAttribute(){
    if($this->compare_price && $this->compare_price >$this->price)
        {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
}

protected static function boot(){
    parent::boot();
    static::creating(function($variant){
        if(empty($variant->sku)){
            $variant->sku='VAR-' . strtoupper(Str::random(8));

        }
    });
}

}
