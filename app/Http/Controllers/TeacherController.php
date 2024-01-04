<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Plan_teacher;
use App\Models\Plan_topic;
use App\Models\Question;
use App\Models\Question_option;
use App\Models\Test;
use App\Models\Testcategory;
use App\Models\Topic;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html as HtmlConverter;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    private function find_elements($arr, $sub_id, $gr_id, $days){
        $found = false;
        foreach($arr as $key => $item){
            if($item["subject"]["id"] == $sub_id and $item["group"]["id"] == $gr_id){
                $found = true;
            }
        }
        return $found;
    }

    public function index(Request $request){
        $groups = Plan_teacher::where('teacher_id', Auth::user()->userid)
                    ->join('plans', 'plans.id', '=', 'plan_teachers.plan_id')
                    ->get();
        return view('teacher.home', ['groups' => $groups]);
    }

    public function class(Request $request){
        if(isset($request->data)){
            $request=$request->data;
        }

        $topics = Topic::where('user_id', Auth::user()->userid)->where('subject_id', $request['subject_id'])->get();
        $lesson = Plan_topic::where('plan_id', $request['plan_id'])->join('topics', 'topics.id', '=', 'plan_topics.topic_id')->get();

        return view('teacher.topic.main', ['data' => $request, 'lesson'=> $lesson, 'topics' => $topics]);
    }

    public function plantopic(Request $request) {
        if(isset($request->data)){
            $request=$request->data;
        }
        $request->validate([
            'topicid' => 'required|integer'
        ]);

        Plan_topic::create([
            'plan_id' => (int) $request->plan_id,
            'topic_id' => (int) $request->topicid
        ]);

        return redirect()->route('teacher.group', ['data' => $request->all()]);
    }
    // Topic
    public function page_create_topic(Request $request){
        $subjects = $this->getlessons();
        return view('teacher.topic.adding_page', ['subjects' => $subjects]);
    }

    public function select_tests(Request $request){
        $tests = Test::where('teacher_id', Auth::user()->userid)->where('subject_id', $request->subject_id)->orderBy('id', 'DESC')->get();
        $html = "<select id='inputState' name='test_id' class='form-select' required>
            <option>-Tanlash-</option>";
        foreach($tests as $item){
            $html = $html . "<option value='". $item['id']."'>".$item['test_name']."</option>";
        }
        $html = $html . "</select>";
        return $html;
    }

    public function create_topic(Request $request){
        $request->validate([
            'subject' => 'required|integer',
            'title' => 'required',
            'fulltext' => 'required',
            'test_id' => 'required|integer'
        ]);
        
        $newtopic = Topic::create([
            'subject_id' => $request->subject,
            'user_id' => Auth::user()->userid,
            'topicname' => $request->title,
            'text' => $request->fulltext,
            'task_id' => $request->test_id,
        ]);

        return redirect()->route('teacher.alltopics');  
    }

    public function get_topics(Request $request){
        $topics = Topic::where('user_id', Auth::user()->userid)->orderBy('id', 'DESC')->get();
        foreach($topics as $item){
            $subj = Http::withToken(config('app.API_token'))->get('https://student.guldu.uz/rest/v1/data/schedule-list?_subject='.$item->subject_id);
            $Data = $subj->json();
            $item->subj_name = $Data['data']['items'][0]['subject']['name'];
            $item->task_name = Test::where('id', $item->task_id)->first()->test_name;
        }
        return view('teacher.topic.alltopics', ['topics' => $topics]);
    }

    public function page_edit_topic(Request $request){
        $edite_les = Topic::where('id',$request->edite_les_id)->first();
        $tests = Test::where('teacher_id', Auth::user()->userid)->where('subject_id', $request['data']['subject_id'])->get();
        return view('teacher.topic.edite', ['data' => $request->data, 'edite_les' => $edite_les, 'tests' => $tests]);
    }

    public function edit_topic(Request $request){
        Topic::where('id', $request->topic_id)->update([
            'topicname'=>$request->title,
            'text'=>$request->fulltext,
            'task_id'=>$request->test_id,
        ]);

        return redirect()->route('teacher.group', ['data' => $request->all()]);
    }

    public function delete_topic(Request $request){
        Plan_topic::where('plan_id', (int) $request->data['plan_id'])->where('topic_id', (int) $request['del_les_id'])->delete();
        $lesson = Plan_topic::where('plan_id', $request->data['plan_id'])->join('topics', 'topics.id', '=', 'plan_topics.topic_id')->get();
        $topics = Topic::where('user_id', Auth::user()->userid)->where('subject_id', $request->data['subject_id'])->get();

        return redirect()->route('teacher.group', ['data' => $request->data]);
    }

    // Test
    public function page_creating_test(Request $request){

        $testcategories = Testcategory::all();

        $lessons = $this->getlessons();
        
        return view('teacher.test.newtest', compact('testcategories', 'lessons'));
    }

    public function create_test(Request $request){

        $request->validate([
            'file' => [File::types(['docx'])]
        ]);
        $file = $request->file('file');
        if ($file) {
            $filePath = $file->getPathname();
            try {
                $phpWord = IOFactory::load($filePath);
            } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
                dd($e->getMessage());
            }
            $tempDir = sys_get_temp_dir();
            $objWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
            $filePath = $tempDir . DIRECTORY_SEPARATOR . 'html_'.time().'generated.html';
            $objWriter->save($filePath); 

            $htmlContent = file_get_contents($filePath);

            $dom = new DOMDocument();
            $dom->loadHTML($htmlContent);

            $xpath = new DOMXPath($dom);
            $paragraphs = $xpath->query('//td');
            $questions = [];
            foreach ($paragraphs as $paragraph) {
                array_push($questions, $dom->saveHTML($paragraph));
            }
            
            return view('teacher.test.checkandsave', ['paragraphs' => $questions, 'aboutTest' => $request->all()]);
        }
        dd("Fayl yuklashda xatolik");
    }

    public function correct_test(Request $request){
        // dd($request->all());
        if(isset($request["save"])){
            $test = Test::create([
                'test_name' => $request->title,
                'teacher_id' => Auth::user()->userid,
                'subject_id' => $request->subject,
                'assessment_id' => 1,
                'testcategory_id' => $request->category,
                'limit_questions'=> $request->count,
                'timer'=> $request->timer,
            ]);

            foreach($request->data as $key => $item){
                if(($request->category == 1 and ($key+1) % 5 == 1) or $request->category != 1){
                    $question = Question::create([
                        'test_id' => $test->id,
                        'question_text' => $item,
                        'level_id' => 1
                    ]);
                }
                else{
                    $correct = 0;
                    if(($key+1) % 5 == 2){
                        $correct = 1;
                    }
                    Question_option::create([
                        'question_id' => $question->id,
                        'option_text' => $item,
                        'correct' => $correct
                    ]);
                }
            }

            return redirect()->route('teacher.alltest');
        }
        else{
            return redirect()->route('teacher.pagecreatingtest');
        }
    }
    // page_edit_test
    // edit_test
    // delete_test
    public function get_tests(Request $request){
        $access_token = config('app.API_token');
        $tests = Test::where('teacher_id', Auth::user()->userid)->get();
        foreach($tests as $item){
            $subj = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?_subject='.$item->subject_id);
            $Data = $subj->json();
            $item->subj_name = $Data['data']['items'][0]['subject']['name'];
            $item->cat_name = Testcategory::where('id', $item->testcategory_id)->first()->category_name;
        }

        return view('teacher.test.alltests', compact('tests'));
    }

    private function filter_g_s($data, $f){
        foreach($data as $item){
            $days = (time() - $item['lesson_date']) / 86340;
            // if(!$this->find_elements($f, $item["subject"]["id"], $item["group"]["id"], $days)){
            $item["until_now"] = $days; 
            array_push($f, $item);
            // } 
        }
        return $f;
    }

    private function filter2($data, $sub_id, $gr_id, $found){
        foreach($data as $item){
            if($item['subject']['id'] == $sub_id and $item['group']['id'] == $gr_id and $item['until_now'] < $found['until_now']){
                $found = $item;
            }
        }
        return $found;
    }

    public function getlessons(){
        $access_token = config('app.API_token');
        $lessons = [];
        $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?_employee='.Auth::user()->userid);
        $Data = $responce->json();
        $done = [];
        for($page = $Data['data']['pagination']['pageCount']; $page > 0; $page--){
            $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?page='.$page.'&_employee='.Auth::user()->userid);
            $Data = $responce->json();
            foreach($Data['data']['items'] as $item){
                if(empty($done[$item['subject']['id']])){
                    $done[$item['subject']['id']] = 1;
                    array_push($lessons, $item['subject']);
                }
            }
        }

        return $lessons;
    }

    public function joining_data(Request $request){
        $access_token = config('app.API_token');
        $lessons = [];
        $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?_employee='.Auth::user()->userid);
        $Data = $responce->json();
        // dd($Data);
        for($page = $Data['data']['pagination']['pageCount']; $page > 0; $page--){
            // dd($page);
            $responce = Http::withToken($access_token)->get('https://student.guldu.uz/rest/v1/data/schedule-list?page='.$page.'&_employee='.Auth::user()->userid);
            $Data = $responce->json();
            $lessons = $this->filter_g_s($Data['data']['items'], $lessons);
        } 
        $filtered = [];
        $fo = [];
        foreach($lessons as $item){
            if(!isset($fo[$item['subject']['id']][$item['group']['id']])){
                $fo[$item['subject']['id']][$item['group']['id']] = 1;
                array_push($filtered, $this->filter2($lessons, $item['subject']['id'], $item['group']['id'], $item));
            }
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
