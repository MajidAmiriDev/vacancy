<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Vacancy;
use Orchid\Screen\Actions\Link;

class VacancyLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'vacancy';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->render(function (Vacancy $vacancy) {
                    return Link::make($vacancy->name)
                        ->route('platform.vacancy.edit', $vacancy);
                }),
            TD::make('price', 'Price')
                ->render(function (Vacancy $vacancy) {
                    return $vacancy->price;
                }),

            TD::make('status', 'Status')
                ->render(function (Vacancy $vacancy) {
                    return $vacancy->status;
                }),

        ];
    }
}
