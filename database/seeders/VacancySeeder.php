<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Vacancy;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancy_instance = Vacancy::create(['name' => 'The Plaza','price'=>10000, 'status'=> true]);

        $vacancy_instance->VacancyAgePriceList()->createMany([
            ['type' => 'adult','additional_amount' => 700],
            ['type' => 'child','additional_amount' => 500],
            ['type' => 'baby','additional_amount' => 300]
        ]);

        $vacancy_instance->VacancyPeriodicPriceList()->createMany([
            ['type' => '+','start_date' => '1692060397', 'end_date' => '', 'additional_amount'=> 600],
            ['type' => '-','start_date' => '1691023597', 'end_date' => '1691196397', 'additional_amount'=> 200],
            ['type' => '+','start_date' => '1691541997', 'end_date' => '', 'additional_amount'=> 300],
            ['type' => '-','start_date' => '1691369197', 'end_date' => '', 'additional_amount'=> 400],
            ['type' => '+','start_date' => '1692751597', 'end_date' => '1692924397', 'additional_amount'=> 900]
        ]);

        $vacancy_instance->VacancyRentedDaysList()->createMany([
            ['start_date' => '1690850797', 'end_date' => ''],
            ['start_date' => '1693269997', 'end_date' => '1693356397'],

        ]);
    }
}
