<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        "order_number",
        "customer_id",
        "coupon_id",
        "subtotal",
        "discount_amount",
        "shopping_cost",
        "tax_amount",
        "total",
        "shipping_full_name",
        "shipping_phone",
        "shipping_address_line_1",
        "shipping_address_line_2",
        "shipping_city",
        "shipping_state",
        "shipping_postal_code",
        "shipping_country",
        "payment_method",
        "payment_status",
        "transaction_id",
        "status",
        "customer_notes",
        "tracing_number",
        "admin_notes",
    ];

    #[Scope()]
    protected function ofStatus(Builder $query, string $status)
    {
        $query->where("payment_status", $status);
    }

    #[Scope()]

    public function payment_status(Builder $query, string $status)
    {
        $query->where("payment_status", $status);
    }
    #[Scope()]

    public function pending(Builder $query)
    {
        $query->where("status", "processing");
    }


    #[Scope()]
    public function shipped(Builder $query)
    {
        $query->where("status", "shipped");
    }
    #[Scope()]
    public function delivered(Builder $query)
    {
        $query->where("status", "delivered");
    }



    // relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }



    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy("created_at", "desc");
    }



    //  helper methods

    public function getShippingAddressAttribute()
    {
        return implode(", ", array_filter([
            $this->shipping_address_line_1,
            $this->shipping_address_line_2,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
            $this->shipping_country,
        ]));
    }
    public function updateStatus($newStatus, $notes = null, $userId = null)
    {
        $this->update(['status' => $newStatus]);
        $this->statusHistories()->create([
            'status' => $newStatus,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating((function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        }));

        static::created(function ($order) {
            $order->statusHistories()->create([
                'status' => $order->status,
                'notes' => 'Order created',
            ]);
            // order confirmation email
        });

    }
}

