<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\VacancyPeriodicPrice;
use App\Models\VacancyAgePrice;
use App\Models\Vacancy;

class VacancyRentedDays extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vacancy_rented_days';
    
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'vacancy_id'
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
