<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

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
        'store_name',
        'company_id',
        'store_sector',
        'product_create',
        'email',
        'city',
        'phone',
    ];

    /**
     * The attributes that are encrypted.
     *
     * @var array <int, string>
     */
    protected $appends = [
        'encrypted_id',
    ];

    /**
     * Get encrypted id.
     *
     * @return string
     */
    public function getEncryptedIdAttribute(): string
    {
        return Hashids::encode($this->attributes['id']);
    }

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
     * Relation: Store has many users.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation: Store has many purchases.
     *
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Relation: Store has many locations.
     *
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Relation: Store has many suppliers.
     *
     * @return HasMany
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * Relation: Store has many waste types.
     *
     * @return HasMany
     */
    public function wasteTypes(): HasMany
    {
        return $this->hasMany(WasteType::class);
    }

    /**
     * Relation: Store has many vat rates.
     *
     * @return HasMany
     */
    public function vatRates(): HasMany
    {
        return $this->hasMany(VatRate::class);
    }

    /**
     * Relation: Store has many departments.
     *
     * @return HasMany
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Relation: Store has many units of measure.
     *
     * @return HasMany
     */
    public function unitsOfMeasure(): HasMany
    {
        return $this->hasMany(UnitOfMeasure::class);
    }

    /**
     * Relation: Store has many stocktakes.
     *
     * @return HasMany
     */
    public function stocktakes(): HasMany
    {
        return $this->hasMany(Stocktake::class);
    }

    /**
     * Relation: Store has many products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation: Store has many labour costs.
     *
     * @return HasMany
     */
    public function labourCosts(): HasMany
    {
        return $this->hasMany(LabourCost::class);
    }

    /**
     * Relation: Store has many wastes.
     *
     * @return HasMany
     */
    public function wastes(): HasMany
    {
        return $this->hasMany(Waste::class);
    }

    /**
     * Relation: Store has many transfers.
     *
     * @return HasMany
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * Relation: Store has many waste users.
     *
     * @return HasMany
     */
    public function wasteUsers(): HasMany
    {
        return $this->hasMany(WasteUser::class, 'store_id', 'id');
    }

    /**
     * Relation: Store has many credit notes.
     *
     * @return HasMany
     */
    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class);
    }

    /**
     * Relation: Store has one master admin.
     *
     * @return HasOne
     */
    public function masterAdmin(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'master_admin');
    }

    /**
     * Relation: Store has many targets.
     *
     * @return HasMany
     */
    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }

    /**
     * Relation: Store has many gp modules.
     *
     * @return HasMany
     */
    public function gpModules(): HasMany
    {
        return $this->hasMany(StoreGpConfiguration::class);
    }

    /**
     * Relation: Store has one last stocktake.
     *
     * @return HasOne
     */
    public function lastStocktake(): HasOne
    {
        return $this->hasOne(Stocktake::class, 'store_id', 'id')
            ->latest('stocktake_date');
    }

    /**
     * Relation: Store has one previous stocktake.
     *
     * @return HasOne
     */
    public function previousStocktake(): HasOne
    {
        return $this->hasOne(Stocktake::class, 'store_id', 'id')
            ->orderBy('stocktake_date', 'DESC')
            ->skip(1)
            ->take(1);
    }

    /**
     * Relation: Enabled modules are related with module.
     *
     * @return mixed
     */
    public function enabledModules(): mixed
    {
        return $this->relation()->with('module');
    }

    /**
     * Relation: Store has many enabled modules.
     *
     * @return HasMany
     */
    public function relation(): HasMany
    {
        return $this->hasMany(StoreEnabledModule::class, 'store_id');
    }

    /**
     * Relation: Store has one currency..
     *
     * @return HasOne
     */
    public function currencyRelation(): HasOne
    {
        return $this->hasOne(StoreCurrency::class, 'store_id');
    }

    /**
     * Relation: Store currency is taken from currency table.
     *
     * @return mixed
     */
    public function storeCurrency(): mixed
    {
        return $this->currencyRelation()->with('currency');
    }
}
