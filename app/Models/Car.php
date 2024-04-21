<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('merek', 'like', '%'.$search.'%');
        });
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaksi::class, "car_id", "id");
    }
}
