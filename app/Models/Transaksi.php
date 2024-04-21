<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "transaksis";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'harga',
        'qty',
        'status'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('tanggal_selesai', 'like', '%'.$search.'%');
            $query->orWhere('harga', 'like', '%'.$search.'%');
            $query->orWhere('status', 'like', '%'.$search.'%');
            $query->orWhereHas('cars', function ($query) use ($search) {
                $query->where('merek', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhere('tarif_sewa', 'like', '%' . $search . '%')
                    ->orWhere('no_plat', 'like', '%' . $search . '%');
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function cars(): BelongsTo
    {
        return $this->belongsTo(Car::class, "car_id", "id");
    }
}
