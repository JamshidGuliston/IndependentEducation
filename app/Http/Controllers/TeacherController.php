<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Plan_teacher;
use App\Models\Plan_topic;
use App\Models\Test;
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
//   dd($request());

        // Topic::where('user_id', )
        $lesson = Plan_topic::where('plan_id', $request['plan_id'])->join('topics', 'topics.id', '=', 'plan_topics.topic_id')->get();

        return view('teacher.topic.main', ['data' => $request, 'lesson'=> $lesson]);
        
    }
    // Topic
    public function page_create_topic(Request $request){
       
        return view('teacher.topic.adding_page', ['data' => $request->all()]);
    }

    public function create_topic(Request $request){
        // dd($request->all());
        // 
        
        $newtopic = Topic::create([
            'user_id' => Auth::user()->userid,
            'topicname' => $request->title,
            'text' => $request->fulltext,
            'task_id' => $request->test_id,
        ]);

        

        Plan_topic::create([
            'plan_id' => $request->plan_id,
            'topic_id' => $newtopic->id
        ]);
        return redirect()->route('teacher.group', ['data' => $request->all()]);
        // return view('teacher.topic.main', ['data' => $request->all(),  'lesson'=> $lesson]);
    }

    public function page_edit_topic(Request $request){
      $edite_les = Topic::where('id',$request->edite_les_id)->first();
    //   dd($request->data);
    return view('teacher.topic.edite', ['data' => $request->data, 'edite_les' => $edite_les]);
    }

    public function edit_topic(Request $request){
        // dd($request->all());
        Topic::where('id', $request->topic_id)->update([
            'topicname'=>$request->title,
            'text'=>$request->fulltext,
            'task_id'=>$request->test_id,
        ]);
    return redirect()->route('teacher.group', ['data' => $request->all()]);
    }

    public function delete_topic(Request $request){
        // dd($request->data['plan_id']);
       Topic::where('id', $request->del_les_id)->first()->delete();

       $lesson = Plan_topic::where('plan_id', $request->data['plan_id'])->join('topics', 'topics.id', '=', 'plan_topics.topic_id')->get();

        return view('teacher.topic.main', ['data' => $request->data, 'lesson'=> $lesson]);
    }

    // Test
    public function page_creating_test(Request $request){

        return view('teacher.test.newtest');
    }

    public function create_test(Request $request){
        $request->validate([
            'file' => [File::types(['docx'])]
        ]);
        dd($request->all());
        $file = $request->file('file');
        if ($file) {
            $filePath = $file->getPathname();
            try {
                $phpWord = IOFactory::load($filePath);
            } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
                // Log or display the exception message
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
            return view('teacher.test.checkandsave', ['paragraphs' => $questions]);

            dd($objWriter);
            $objWriter->save(public_path('uploads\test3.html'));
            dd($objWriter);
            // $html = HtmlConverter::convert($phpWord, $imageDir);

            // Save HTML to a file
            $htmlFilePath = public_path('converted.html'); // Adjust as needed
            file_put_contents($htmlFilePath, $html);

            return response()->json(['message' => 'Word file converted to HTML']);
        
        }
        dd(1);
    }

    public function correct_test(Request $request){
        // dd($request->all());
        if(isset($request["save"])){
        //     $test = Test::create([
        //         'test_name' => ,
        //         'teacher_id' => ,
        //         'subject_id' => ,
        //         'assessment_id' => 1,
        //         'testcategory_id' => ,
        //         'limit' => 
        //     ]);
        }
        else{
            return redirect()->route('teacher.pagecreatingtest');
        }
    }
    // page_edit_test
    // edit_test
    // delete_test
    public function get_tests(Request $request){

        return view('teacher.test.alltests');
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
