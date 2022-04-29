<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    
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
