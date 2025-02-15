<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImage;
use App\Traits\HasStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class SubShipments extends Model
{
    use HasFactory;

    protected $table = 'shipment_sub_package';

    protected $fillable = [
        'supplier_name',
        'bar_code',
        'weight',
        'price',
        'shipment_id'
        
    ];

    protected $casts = [
         'supplier_name' => 'array',
         'weight' => 'array',
         'price' => 'array',
         'bar_code' => 'array'
       ];
    
    /**
     * Scope a query to search posts
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;
        return $query->where('title', 'LIKE', "%{$search}%");
    }

}
