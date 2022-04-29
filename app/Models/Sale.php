<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'consigned_product_id',
        'quantity_sold',
    ];

    /**
     * Get the invoice that owns the sale.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the consigned product that owns the sale.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consignedProduct()
    {
        return $this->belongsTo(ConsignedProduct::class);
    }
}
