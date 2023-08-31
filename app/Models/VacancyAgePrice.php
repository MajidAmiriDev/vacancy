<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\VacancyPeriodicPrice;
use App\Models\Vacancy;
use App\Models\VacancyRentedDays;

class VacancyAgePrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vacancy_age_price';
    
    use HasFactory;
    protected $fillable = [
        'type',
        'additional_amount',
        'vacancy_id'
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
