<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentBox extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'customer_id',
        'delivery_id', // New field
        'zone',        // New field
        'box_name',
        'box_barcode',
        'box_weight',
        'box_price',
        'box_shipment_charge',
        'box_shipping_date',
        'box_delivery_date',
        'vat',
        'tax',
    ];

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipments::class, 'shipment_id', 'id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Scope a query to filter by search input.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('box_name', 'LIKE', "%{$search}%")
              ->orWhere('box_barcode', 'LIKE', "%{$search}%")
              ->orWhereHas('customer', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
              });
        });
    }
}
