<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasUuidTrait;

class ConsignOrder extends Model
{
    use HasFactory, HasUuidTrait;
    
    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'user_id',
        'order_sent_at',
        'order_delivered_at',
        'delivered_by',
    ];

    /**
     * Get the user that owns the consign order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the supplier that owns the consign order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the consigned products of the consign order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consignedProducts()
    {
        return $this->hasMany(ConsignedProduct::class);
    }
}
