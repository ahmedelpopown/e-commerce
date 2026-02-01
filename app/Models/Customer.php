<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
 protected $fillable=[
    "name",
    "email",
    "phone",
    "password",
    "date_of_birth",
    "gender",
    "is_active",
 ];
 protected $hidden=[
    "password",
    "remember_token",
 ];

 protected function casts(){
    return [
        "email_verified_at"=>"datetime",
        "password"=>"hashed",
        "date_of_birth"=>"date",
        "is_active"=>"boolean",
    ];
 }

   #[Scope()]
    protected function active(Builder $builder)
    {
        $builder->where('is_active', true);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }
    public function defaultAddresses(){
        return $this->hasOne(Address::class)->where("is_default",true);
        }
        public function orders(){
            return $this->hasMany(Order::class);
        }
        public function reviews(){
            return $this->hasMany(Review::class);
        }
        public function couponUsages(){
            return $this->hasMany(CouponUsage::class);
        }

        public function getTotalSpentAttribute(){
            return $this->orders()->where("payment_status",'paid')->sum('total');
        }
        public function getOrdersCountAttribute(){
            return $this->orders()->count();
        }
}
