<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    /**
     * Get the pay_items for the business.
     */
    public function payItems()
    {
        return $this->hasMany(PayItem::class);
    }

    /**
     * The users that belong to the business.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
