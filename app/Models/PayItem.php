<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */    
    protected $fillable = [
        'business_id',
        'user_id',
        'external_id',
        'amount_paid_in_cents',
        'time_worked',
        'hourly_rate_in_cents',
        'paid_at',
    ];

    /**
     * Get the business with the pay_item.
     */
    public function business()
    {
        return $this->hasOne(Business::class);
    }

    /**
     * Get the user with the pay_item.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
