<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request){
        // dd(Auth::user());
        return view('student.home', ['user' => Auth::user()]);
    }
}
