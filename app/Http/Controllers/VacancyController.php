<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VacancyRequest;
use App\Models\Vacancy;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Http\Traits\VacancyTrait;

class VacancyController extends Controller
{
    use VacancyTrait;

    public function index(Request $request)
    {
        return view('welcome', ['vacancy_id' => $request->vacancy_id]);
    }

    public function events(Request $request)
    {
        //If we had an edit, I would definitely cache and delete it after every change
        $identifier_key_pattern = 'events:v1:vacancy:%s';
        $identifier_key_vacancy = sprintf($identifier_key_pattern, $request->vacancy_id.'_'.strtotime($request->start));
        $check_cache_vacancy = $this->checkCache($identifier_key_vacancy);  
        if ($check_cache_vacancy) {
            $outputFromCache = $this->getFromCache($identifier_key_vacancy);
            echo json_encode($outputFromCache);    
            exit;
        }
        $vacancy = Vacancy::find($request->vacancy_id);
        $vacancy_periodic_price_list = $vacancy->VacancyPeriodicPriceList()->whereBetween('start_date', [strtotime($request->start),strtotime($request->end)])->get();
        $vacancy_rented_days_list = $vacancy->VacancyRentedDaysList()->whereBetween('start_date', [strtotime($request->start),strtotime($request->end)])->get()->toArray();
        $vacancy_age_price_list = $vacancy->VacancyAgePriceList->toArray();
        $full_days_array = $this->CreateArrayOfDates($request->start, $request->end, $vacancy->price);
        $merge_with_periodic = $this->MergeWithPeriodic($full_days_array, $vacancy_periodic_price_list);
        $merge_with_rented = $this->MergeWithRented($merge_with_periodic, $vacancy_rented_days_list);
        $full_days_array = array_values($merge_with_rented);
        $this->setInCache($identifier_key_vacancy, $full_days_array);
        echo json_encode($full_days_array);

    }

    public function calculate(Request $request){
        $vacancy = Vacancy::find($request->vacancy_id);
        $vacancy_periodic_price_list = $vacancy->VacancyPeriodicPriceList()->whereBetween('start_date', [strtotime($request->start),strtotime($request->end)])->get();
        $vacancy_rented_days_list = $vacancy->VacancyRentedDaysList()->whereBetween('start_date', [strtotime($request->start),strtotime($request->end)])->get()->toArray();
        $vacancy_age_price_list = $vacancy->VacancyAgePriceList->toArray();
        $full_days_array = $this->CreateArrayOfDates($request->start, $request->end, $vacancy->price);
        $merge_with_periodic = $this->MergeWithPeriodic($full_days_array, $vacancy_periodic_price_list);
        $merge_with_rented = $this->MergeWithRented($merge_with_periodic, $vacancy_rented_days_list);
        $full_days_array = array_values($merge_with_rented);
        //$result = $this->checkAndGetPrice();
        $sum_price = 0;
        $vacancy_status = true;
        foreach($full_days_array as $data){
            if($data['title'] != "Out of reach"){
                $sum_price += (int)$data['title'];
            }else{
                return response()->json([
                    'status' => 503,
                    'message' => 'Be careful in the selection period'
                ], 503);
            }
        }
        $pluse_per_human = 0;
        foreach($vacancy_age_price_list as $age){
            if($age['type'] == 'adult'){
                $pluse_per_human += (int)$request->adult * (int)$age['additional_amount'] * count($full_days_array);
            }elseif($age['type'] == 'child'){
                $pluse_per_human += (int)$request->child * (int)$age['additional_amount'] * count($full_days_array);
            }else{
                $pluse_per_human += (int)$request->baby * (int)$age['additional_amount'] * count($full_days_array);
            }
        }
        return response()->json([
            'status' => 200,
            'data' => [
                'price' => $sum_price + $pluse_per_human,
            ]
        ], 200);
    }

    public function MergeWithRented($full, $rented){
        $interval = new DateInterval('P1D');
        $range = array();
        $counter_days = 1;
        foreach ($rented as $p) {
            if($p['end_date'] != ""){
                $endPlusOne= date_create(date('Y-m-d', $p['end_date']))->add(new DateInterval('P1D'));
                $date_range = new DatePeriod(date_create(date('Y-m-d', $p['start_date'])), $interval, $endPlusOne );
                foreach ($date_range as $date) {
                    $full[$date->format('Y-m-d')]['title'] = 'Out of reach'; 
                }
            }else{
                $full[date('Y-m-d', $p['start_date'])]['title'] = 'Out of reach'; 
                  
            }

        }
        return $full;
    }



    public function MergeWithPeriodic($full, $periodic){
        $interval = new DateInterval('P1D');
        $range = array();
        $counter_days = 1;
        foreach ($periodic as $p) {
            if($p['end_date'] != ""){
                $endPlusOne= date_create(date('Y-m-d', $p['end_date']))->add(new DateInterval('P1D'));
                $date_range = new DatePeriod(date_create(date('Y-m-d', $p['start_date'])), $interval, $endPlusOne );
                foreach ($date_range as $date) {
                    if($p['type'] == '+'){
                        $full[$date->format('Y-m-d')]['title'] = (int)$full[$date->format('Y-m-d')]['title']+(int)$p['additional_amount']; 
                    }else{
                        $full[$date->format('Y-m-d')]['title'] = (int)$full[$date->format('Y-m-d')]['title']-(int)$p['additional_amount'];  
                    }
                }
            }else{
                if($p['type'] == '+'){
                    $full[date('Y-m-d', $p['start_date'])]['title'] = (int)$full[date('Y-m-d', $p['start_date'])]['title']+(int)$p['additional_amount']; 
                }else{
                    $full[date('Y-m-d', $p['start_date'])]['title'] = (int)$full[date('Y-m-d', $p['start_date'])]['title']-(int)$p['additional_amount']; 
                }
                  
            }

        }
        return $full;
    }

    public function CreateArrayOfDates($start, $end, $defaultPrice)
    {
        $result = array();
        $interval = new DateInterval('P1D');
        $date_range = new DatePeriod(date_create($start), $interval, date_create($end));
        $counter_days = 1;
        foreach ($date_range as $date) {
            $result[$date->format('Y-m-d')] = array(
                'id'=> $counter_days ,
                'title'=> $defaultPrice,
                'start'=> $date->format('Y-m-d'),
                'end'=> $date->format('Y-m-d'),
                'allDay' => true,
                'color' => 'black',
                'textColor'=> 'yellow'
            );
            $counter_days++;
        }
        return $result;
    }

    public function showOne(VacancyRequest $request)
    {
        if ($request->errors_form) {
            return response()->json([
                'status' => 422,
                'data' => $request->errors_form
            ], 422);
        }

        $vacancy_id = $request->input('vacancy_id');
        return response()->json([
            'status' => 200,
            'data' => [
                'message' => 'Otp code sent successfully',
            ]
        ], 200);
    }



}
