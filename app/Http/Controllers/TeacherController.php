<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Plan_teacher;
use App\Models\Plan_topic;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TeacherController extends Controller
{
    private function find_elements($arr, $sub_id, $gr_id){
        $found = false;
        foreach($arr as $item){
            if($item["subject"]["id"] == $sub_id and $item["group"]["id"] == $gr_id){
                $found = true;
            }
        }
        return $found;
    }
    private function filter_g_s($data, $f){
        foreach($data as $item){
            if(!$this->find_elements($f, $item["subject"]["id"], $item["group"]["id"])){
                array_push($f, $item);
            } 
        }
        return $f;
    }
    public function index(Request $request){
        $groups = Plan_teacher::where('teacher_id', Auth::user()->userid)
                    ->join('plans', 'plans.id', '=', 'plan_teachers.plan_id')
                    ->get();

        return view('teacher.home', ['groups' => $groups]);
    }

    public function class(Request $request){
        // dd($request->all());
        return view('teacher.topic.main', ['data' => $request->all()]);
    }

    public function page_create_topic(Request $request){
        
        return view('teacher.topic.adding_page', ['data' => $request->all()]);
    }

    public function create_topic(Request $request){
        // dd($request->all());
        $plan_id = Plan::where('subject_id', $request->subject_id)->where('group_id', $request->group_id)->where('semester_id', $request->semester_id)->first()->id;
        
        $newtopic = Topic::create([
            'user_id' => Auth::user()->userid,
            'topicname' => $request->title,
            'text' => $request->fulltext,
            'task_id' => $request->test_id,
        ]);

        Plan_topic::create([
            'plan_id' => $plan_id,
            'topic_id' => $newtopic->id
        ]);

        return redirect()->route('teacher.group', ['data' => $request->all()]);
    }

    public function page_edit_topic(Request $request){

    }

    public function edit_topic(Request $request){
        
    }

    public function delete_topic(Request $request){

    }


    public function joining_data(Request $request){
        $access_token = config('app.API_token');
        $filtered = [];
        $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?page=1&limit=200&_employee='.Auth::user()->userid);
        $Data = $responce->json();
        $filtered = $this->filter_g_s($Data['data']['items'], $filtered);
        for($page = 2; $page <= $Data['data']['pagination']['pageCount']; $page++){
            $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?page='.$page.'&limit=200&_employee='.Auth::user()->userid);
            $Data = $responce->json();
            $filtered = $this->filter_g_s($Data['data']['items'], $filtered);
        } 
        foreach($filtered as $item){
            $select = Plan::where('semester_id', $item["semester"]["code"])
                        ->where('subject_id', $item["subject"]["id"])
                        ->where('trainingTypeCode', $item["trainingType"]["code"])
                        ->where('group_id', $item["group"]["id"])->first();
            if($select == null){
                $plan = Plan::create([
                    'subject_id' => $item["subject"]["id"],
                    'trainingTypeCode' => $item["trainingType"]["code"],
                    'group_id' => $item["group"]["id"],
                    'group_name' => $item["group"]["name"],
                    'subject_name' => $item["subject"]["name"],
                    'trainingTypeName' => $item["trainingType"]["name"],
                    'year_id' => $item["educationYear"]["code"],
                    'semester_id' => $item["semester"]["code"],
                    'isactive' => true,
                ]);

                Plan_teacher::create([
                    'plan_id' => $plan->id,
                    'teacher_id' => $item["employee"]["id"],
                    'last_teacher' => true
                ]);
            }
        }

        return redirect()->route('teacher.home');
    }
}
