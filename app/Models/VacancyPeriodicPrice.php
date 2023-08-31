<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Vacancy;
use App\Models\VacancyAgePrice;
use App\Models\VacancyRentedDays;

class VacancyPeriodicPrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vacancy_periodic_price';
    protected $datefromtime;
    protected $enddatefromtime;
    
    use HasFactory;
    protected $fillable = [
        'additional_amount',
        'start_date',
        'end_date',
        'type',
        'vacancy_id'
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }


    public function getDatefromtime()
    {
        return $this->datefromtime;
    }

    public function setDatefromtime($value)
    {
        $this->datefromtime = $value;
    }


    public function getEnddatefromtime()
    {
        return $this->enddatefromtime;
    }

    public function setEnddatefromtime($value)
    {
        $this->enddatefromtime = $value;
    }
}
