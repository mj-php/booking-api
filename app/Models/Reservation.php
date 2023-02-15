<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array <int, string>
     */
    protected $hidden = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'element_id',
    ];

    /**
     * Reformat created at date.
     *
     * @return string
     */
    public function getCreatedAtAttribute($date): string
    {
        return Carbon::createFromFormat("Y-m-d\TH:i:s.u\Z", $date)->format('Y-m-d H:i:s');
    }

    /**
     * Reformat updated at date.
     *
     * @return string
     */
    public function getUpdatedAtAttribute($date): string
    {
        return Carbon::createFromFormat("Y-m-d\TH:i:s.u\Z", $date)->format('Y-m-d H:i:s');
    }

    /**
     * Relation: Reservation is connected to one reserved element.
     *
     * @return BelongsTo
     */
    public function reservedElement(): BelongsTo
    {
        return $this->belongsTo(Element::class);
    }
}
