<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\VacancyPeriodicPrice;
use App\Models\VacancyAgePrice;
use App\Models\VacancyRentedDays;
use Orchid\Screen\AsSource;


class Vacancy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vacancy';
    
    use HasFactory,AsSource;
    protected $fillable = [
        'name',
        'price',
        'status'
    ];

    public function VacancyAgePriceList(): HasMany
    {
        return $this->hasMany(VacancyAgePrice::class);
    }

    public function VacancyPeriodicPriceList(): HasMany
    {
        return $this->hasMany(VacancyPeriodicPrice::class);
    }

    public function VacancyRentedDaysList(): HasMany
    {
        return $this->hasMany(VacancyRentedDays::class);
    }
}
