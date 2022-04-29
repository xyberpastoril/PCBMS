<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignedProduct extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consign_order_id',
        'product_id',
        'quantity',
        'unit',
        'particulars',
    ];

    /**
     * Get the product that owns the consign product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the consign order that owns the consign product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consignOrder()
    {
        return $this->belongsTo(ConsignOrder::class);
    }

    /**
     * Get the sales of the consigned product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
