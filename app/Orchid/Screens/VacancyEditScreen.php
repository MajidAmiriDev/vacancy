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
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;

class VacancyEditScreen extends Screen
{
    public $vacancy;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Vacancy $Vacancy,Request $request): iterable
    {
        $product = new Vacancy();
        $main_vacancy = Vacancy::find($request->route()->originalParameters()['vacancy_id']);
        $vacancy_age_price = VacancyAgePrice::where('vacancy_id', $request->route()->originalParameters()['vacancy_id'])->get()->toarray();
        $vacancy_periodic_price = VacancyPeriodicPrice::where('vacancy_id', $request->route()->originalParameters()['vacancy_id'])->get()->toarray();
        $vacancy_rented_days = VacancyRentedDays::where('vacancy_id', $request->route()->originalParameters()['vacancy_id'])->get()->toarray();
        $this->vacancy = $main_vacancy;
        return [
            'vacancy' => $main_vacancy,
            'vacancy_age_price' => $vacancy_age_price,
            'vacancy_periodic_price' => $vacancy_periodic_price,
            'vacancy_rented_days' => $vacancy_rented_days
        ];

    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'VacancyEditScreen';
        return $this->vacancy->exists ? 'Edit vacancy' : 'Creating a new vacancy';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create vacancy')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->vacancy->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->vacancy->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->vacancy->exists),

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
                Layout::rows([
                    Input::make('vacancy.name')
                        ->title('Name'),
                    Input::make('vacancy.price')
                        ->title('Price')
                        ->type('number'),
                    CheckBox::make('vacancy.status')
                        ->value(1)
                        ->title('status ?'),
                    Matrix::make('vacancy_age_price')
                        ->title('vacancy age price')
                        ->columns([
                            'type',
                            'additional_amount',
                        ]),
                    Matrix::make('vacancy_periodic_price')
                        ->title('vacancy periodic price')
                        ->columns(['additional_amount', 'start_date', 'end_date', 'type'])
                        ->fields([
                            'additional_amount'   => Input::make()->title('additional_amount'),
                            'start_date' => Input::make()->title('start_date'),
                            'end_date' => Input::make()->title('end_date'),
                            'type' => Input::make()->title('type'),
                        ]),

                    Matrix::make('vacancy_rented_days')
                        ->title('vacancy rented days')
                        ->columns(['start_date', 'end_date'])
                        ->fields([
                            'start_date'   => Input::make()->title('start_date'),
                            'end_date' => Input::make()->title('end_date'),
                        ]),



                ])

        ];
    }



   /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $data = $request->get('vacancy');
        $data['status'] = 0;
        if($request->input('vacancy.status') !== null){
            $data['status'] = 1;
        }
        $this->vacancy = Vacancy::find($request->route()->originalParameters()['vacancy_id']);
        $this->vacancy->fill($data)->save();
        if($request->input('vacancy_age_price') !== null){
            VacancyAgePrice::where('vacancy_id', $this->vacancy->id)->delete();
            foreach($request->input('vacancy_age_price') as $op){
                $po_instance = new VacancyAgePrice();
                $po_instance->type = $op['type'];
                $po_instance->additional_amount = $op['additional_amount'];
                $po_instance->vacancy_id = $this->vacancy->id;
                $po_instance->save();
            }
        }
        if($request->input('vacancy_periodic_price') !== null){

            foreach($request->input('vacancy_periodic_price') as $faq){
                $faq_instance = new VacancyPeriodicPrice();
                $faq_instance->additional_amount = $faq['additional_amount'];
                $faq_instance->start_date = $faq['start_date'];
                $faq_instance->end_date = $faq['end_date'];
                $faq_instance->type = $faq['type'];
                $faq_instance->vacancy_id = $this->vacancy->id;
                $faq_instance->save();
            }
        }else{
            VacancyPeriodicPrice::where('vacancy_id', $this->vacancy->id)->delete();
        }
        if($request->input('vacancy_rented_days') !== null){
            VacancyRentedDays::where('vacancy_id', $this->vacancy->id)->delete();
            foreach($request->input('vacancy_rented_days') as $pi){
                $pi_instance = new VacancyRentedDays();
                $pi_instance->start_date = $pi['start_date'];
                $pi_instance->end_date = $pi['end_date'];
                $pi_instance->vacancy_id = $this->vacancy->id;
                $pi_instance->save();
            }
        }
        Alert::info('You have successfully created a product.');



        return redirect()->route('platform.vacancy');
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Vacancy $Vacancy,Request $request)
    {
        $this->vacancy = Vacancy::find($request->route()->originalParameters()['vacancy_id']);
        $this->vacancy->delete();

        Alert::info('You have successfully deleted the product.');

        return redirect()->route('platform.vacancy');
    }


}
