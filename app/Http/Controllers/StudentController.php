<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Plan_topic;
use App\Models\Question;
use App\Models\Question_option;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function index(Request $request){
        // dd(Auth::user());
        $responce = Http::withToken(config('app.API_token'))->get('https://student.guldu.uz/rest/v1/data/student-list?page=1&limit=1&search='.Auth::user()->hid);
        $S_Data = $responce->json();
        $group_id = $S_Data['data']['items'][0]['group']['id'];
        $group_id = 787;
        // dd($group_id);
        $semester_code = $S_Data['data']['items'][0]['semester']['code'];
        $semester_code = 15;
        $subjects = Plan::where('group_id', $group_id)->where('semester_id', $semester_code)->get();
        // dd($subjects);

        return view('student.home', ['user' => Auth::user(), 'subjects' => $subjects]);
    }

    public function insubject(Request $request){
        $topics = Plan_topic::where('plan_id', $request->id)
            ->join('topics', 'topics.id', '=', 'plan_topics.topic_id')
            ->get();
        // dd($topics);

        return view('student.insubject', ['topics' => $topics]);
    }

    public function takequestions(Request $request){
        $test = Test::where('tests.id', $request->testid)
                ->join('testcategories', 'testcategories.id', '=', 'tests.testcategory_id')
                ->first();
        $questions = Question::where('test_id', $request->testid)->get();
        $html = "";

        if($test->testcategory_id == 1){
            $tr = 1;
            foreach($questions as $key => $item){

                $html = $html . "<ul class='list-group'>
                    <li class='list-group-item' aria-current='true'><span style='color: red'>".$tr." - savol</span>".$item->question_text."</li>";

                $options = Question_option::where('question_id', $item->id)->get();
                
                $html = $html."<li class='list-group-item'><input class='form-check-input' type='radio' name='results[".$tr."]' value=''><label class='form-check-label' for='gridRadios1'>". $options[0]['option_text']."
                  </label></li>
                    <li class='list-group-item'><input class='form-check-input' type='radio' name='results[".$tr."]' value='option2'>". $options[1]['option_text']."</li>
                    <li class='list-group-item'><input class='form-check-input' type='radio' name='results[".$tr."]' value='option2'>". $options[2]['option_text']."</li>
                    <li class='list-group-item'><input class='form-check-input' type='radio' name='results[".$tr."]' value='option2'>". $options[3]['option_text']."</li>
                </ul><br>";
                $tr++;
            }
        }
        else{

        }

        return $html;
    }

    
}
