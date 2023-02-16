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
     * Relation: Reserved element has one reservation.
     *
     * @return HasOne
     */
    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class);
    }
}
