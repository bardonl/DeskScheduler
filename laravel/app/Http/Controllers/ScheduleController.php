<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function saveTime(Request $request){
        $rules = [
            'day' => 'required|min:0|max:6',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = (array)$validator->messages();
            $error = $messages[array_key_first($messages)];
            return response($error,400);

        } else {
            $data['day'] = $request['day'];
            $data['start_time'] = $request['startTime'];
            $data['end_time'] = $request['endTime'];

            $schedule = Schedule::query()->findOrNew($request['id']);

            if($schedule->fill($data)->save()){
                $data['id'] = $schedule->id;
                return response($data,200);
            } else {
                return response(['error'=>['message'=>'Time failed to save']],400);
            }
        }
    }

    public function getScheduled()
    {
        $scheduled = Schedule::query()->select(['id','day','start_time','end_time'])->orderBy('day','ASC')->get();
        $scheduled = $scheduled->toArray();;

        $days = [];

        foreach ($scheduled as $scheduledItem){
            for($d = 0; $d < 7; $d++){
                if($scheduledItem['day'] === $d){
                    $end = new DateTime($scheduledItem['end_time']);
                    $start = new DateTime($scheduledItem['start_time']);

                    $diff = $end->diff($start);
                    $q = $start->format('i')/15;
                    $h = $start->format('H');
                    $scheduledItem['start_time'] = $start->format('H:i');
                    $scheduledItem['end_time'] = $end->format('H:i');
                    $scheduledItem['q_amount'] = ($diff->h*4) + ($diff->i/15);
                    $scheduledItem['h_start'] = substr($h, 1,1);;
                    $scheduledItem['q_start'] = $q;
                    $days[$d][] = $scheduledItem;
                }
            }
        }

        return $days;
    }

    public function deleteTime(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = (array)$validator->messages();
            $error = $messages[array_key_first($messages)];
            return response($error,400);

        } else {
            if(Schedule::query()->where('id',$request['id'])->delete()){
                return response('deleted',200);
            } else {
                return response(['error'=>['message'=>'Time failed to save']],400);
            }
        }
    }
}
