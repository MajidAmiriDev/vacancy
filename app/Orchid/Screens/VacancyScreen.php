<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\VacancyAgePrice;
use App\Models\VacancyPeriodicPrice;
use App\Models\VacancyRentedDays;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\CheckBox;
use App\Orchid\Layouts\VacancyLayout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\TextArea;


class VacancyScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'vacancy' => Vacancy::paginate()
        ];

    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'VacancyScreen';
    }




    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        $request->validate([
            'vacancy.name' => 'required|max:255',
        ]);


        $vacancy = new Vacancy();
        $vacancy->name = $request->input('vacancy.name');
        $vacancy->price = $request->input('vacancy.price');
        $status = 0;
        if($request->input('vacancy.status') !== null){
            $status = 1;
        }
        $vacancy->status = $status;
        $vacancy->save();
        if($request->input('vacancy.age_price') !== null){
            foreach($request->input('vacancy.age_price') as $op){
                $po_instance = new VacancyAgePrice();
                $po_instance->type = $op['type'];
                $po_instance->additional_amount = $op['additional_amount'];
                $po_instance->vacancy_id = $vacancy->id;
                $po_instance->save();
            }
        }

        if($request->input('vacancy.periodic_price') !== null){
            foreach($request->input('vacancy.periodic_price') as $faq){
                $faq_instance = new VacancyPeriodicPrice();
                $faq_instance->additional_amount = $faq['additional_amount'];
                $faq_instance->start_date = $faq['start_date'];
                $faq_instance->end_date = $faq['end_date'];
                $faq_instance->type = $faq['type'];
                $faq_instance->vacancy_id = $vacancy->id;
                $faq_instance->save();
            }
        }
        if($request->input('vacancy.rented_days') !== null){
            foreach($request->input('vacancy.rented_days') as $pi){
                $pi_instance = new VacancyRentedDays();
                $pi_instance->start_date = $pi['start_date'];
                $pi_instance->end_date = $pi['end_date'];
                $pi_instance->vacancy_id = $vacancy->id;
                $pi_instance->save();

            }
        }


    }







    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Vacancy')
                ->modal('productModal')
                ->method('create')
                ->icon('plus'),
        ];

    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            VacancyLayout::class,
            Layout::modal('productModal', Layout::rows([
                Group::make([
                    Input::make('vacancy.name')
                        ->title('Name'),
                ]),

                Group::make([
                    Input::make('vacancy.price')
                        ->title('Price')
                        ->type('number'),
                    CheckBox::make('vacancy.status')
                        ->value(1)
                        ->title('status ?'),
                ]),

                Matrix::make('vacancy.age_price')
                    ->title('vacancy age price')
                    ->columns([
                        'type',
                        'additional_amount',
                    ]),


                Matrix::make('vacancy.periodic_price')
                    ->title('Fvacancy periodic price')
                    ->columns(['additional_amount', 'start_date', 'end_date', 'type'])
                    ->fields([
                        'additional_amount'   => Input::make()->title('additional_amount'),
                        'start_date' => Input::make()->title('start_date'),
                        'end_date' => Input::make()->title('end_date'),
                        'type' => Input::make()->title('type'),
                    ]),



                Matrix::make('vacancy.rented_days')
                    ->title('vacancy rented days')
                    ->columns(['start_date', 'end_date'])
                    ->fields([
                        'start_date'   => Input::make()->title('start_date'),
                        'end_date' => Input::make()->title('end_date'),
                    ]),

            ]))
                ->title('Create Vacancy')
                ->size(Modal::SIZE_LG)
                ->applyButton('Add Vacancy'),
        ];


    }
}
