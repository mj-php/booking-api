<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Element extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-d-m H:i:s',
        'updated_at' => 'datetime:Y-d-m H:i:s',
    ];

    /**
     * Relation: Reserved element has one reservation.
     *
     * @return HasOne
     */
    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class);
    }
}
