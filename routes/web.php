<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'student', 'middleware' => ['isStudent', 'auth']], function () {
    Route::get('home', [StudentController::class, 'index'])->name('student.home');
});

Route::group(['prefix' => 'teacher', 'middleware' => ['isTeacher', 'auth']], function () {
    Route::post('joiningdata', [TeacherController::class, 'joining_data'])->name('teacher.getting');
    Route::get('home', [TeacherController::class, 'index'])->name('teacher.home');
    Route::get('class', [TeacherController::class, 'class'])->name('teacher.group');
    // for topic
    Route::get('page_create_topic', [TeacherController::class, 'page_create_topic'])->name('teacher.pageaddingtopic');
    Route::post('create_topic', [TeacherController::class, 'create_topic'])->name('teacher.addtopic');
    Route::post('page_edit_topic', [TeacherController::class, 'page_edit_topic'])->name('teacher.pageedittopic');
    Route::post('edit_topic', [TeacherController::class, 'edit_topic'])->name('teacher.edittopic');
    Route::post('delete_topic', [TeacherController::class, 'delete_topic'])->name('teacher.deletetopic');
    // for Test
    Route::get('alltests', [TeacherController::class, 'get_tests'])->name('teacher.alltest');
    Route::get('page_creating_test', [TeacherController::class, 'page_creating_test'])->name('teacher.pagecreatingtest');
    Route::post('create_test', [TeacherController::class, 'create_test'])->name('teacher.addtest');
    Route::post('correct_test', [TeacherController::class, 'correct_test'])->name('teacher.correcttest');
    Route::post('page_edit_test', [TeacherController::class, 'page_edit_test'])->name('teacher.pageedittest');
    Route::post('edit_test', [TeacherController::class, 'edit_test'])->name('teacher.edittest');
    Route::post('delete_test', [TeacherController::class, 'delete_test'])->name('teacher.deletetest');
    
});

require __DIR__.'/auth.php';
