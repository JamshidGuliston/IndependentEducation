<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private $access_token = "mM7veNzwzaGLzkw1YW30fqQpxTo840wu";

    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function checkuser($login, $password){
        $role = 0;
        $responce = Http::post('https://student.guldu.uz/rest/v1/auth/login',[
            'login' => $login,
            'password' => $password
        ]);
        $Data = $responce->json();

        if($Data["success"] == True){
            $responce = Http::withToken($this->access_token)->get('https://student.guldu.uz/rest/v1/data/student-list?page=1&limit=1&search='.$login);
            $S_Data = $responce->json();
            if (isset($S_Data["data"]["items"]["0"]) and $S_Data["data"]["items"]["0"]["studentStatus"]["name"] == "O‘qimoqda"){
                $S_Data = $S_Data["data"]["items"]["0"]; 
                $role = 2;  
            }
            // dd($S_Data);
        }
        else{
            $responce = Http::withToken($this->access_token)->get('https://student.guldu.uz/rest/v1/data/employee-list?type=teacher&limit=1&search='.$login);
            $E_Data = $responce->json();
            if (isset($E_Data["data"]["items"]["0"]) and $E_Data["data"]["items"]["0"]["employeeStatus"]["name"] == "Ishlamoqda"){
                $E_Data = $E_Data["data"]["items"]["0"];
                $role = 3;
            }
            // dd($E_Data);
        }

        if($role == 2){
            $st = User::where('hid', '=', $S_Data["student_id_number"])->first();
            if(!isset($st->hid)){
                $d['name'] = $S_Data["short_name"];
                $d['H_ID'] = $S_Data["student_id_number"];
                $d["H_Login"] = $password;
                $d["H_img"] = $S_Data["image"];
                $d['role'] = $role;
                return $d;
            }
        }
        elseif($role == 3){
            $tch = User::where('hid', '=', $E_Data["employee_id_number"])->first();
            if(!isset($tch->hid)){
                $d['name'] = $E_Data["short_name"];
                $d['H_ID'] = $E_Data["employee_id_number"];
                $d["H_Login"] = "null";
                $d["H_img"] = $E_Data["image"];
                $d['role'] = $role;

                return $d;
            }
        }

        return $role;
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'min:2', 'max:30', 'unique:'.User::class],
            'password' => ['required'],
        ]);
        // dd($request->all());

        $data = $this->checkuser($request->email, $request->password);

        if(isset($data['name'])){
            $user = User::create([
                'name' => $data['name'],
                'hid' => $data['H_ID'],
                'hpassword' => $request->password,
                'himg' => $data['H_img'],
                'email' => 'hemis'.$data['H_ID']."@gmail.com",
                'password' => Hash::make($request->password),
                'role_id' => $data['role']
            ]);

            event(new Registered($user));
        }
        elseif($data == 0){
            return redirect('login');
        }
        else{
           $user = User::where('hid', $request->email)->first();
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
