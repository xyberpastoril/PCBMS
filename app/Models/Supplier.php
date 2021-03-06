<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasUuidTrait;

class Supplier extends Model
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
        'name',
        'email',
        'mobile_number',
        'physical_address',
    ];

    /**
     * Get the consign orders of the supplier.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consignOrders()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the products of the supplier.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
