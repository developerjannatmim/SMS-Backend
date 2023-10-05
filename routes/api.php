<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

//Admin routes start here
Route::controller(AdminController::class)->group(function () {

  //Route::get('admin/dashboard', 'adminDashboard')->name('admin.dashboard')->middleware('role_id');

  //Admin users route
  Route::group(['prefix' => 'admin'], function () {
    Route::get('', 'admin_list');
    Route::post('', 'admin_store');
    Route::group(['prefix' => '{admin}'], function () {
      Route::get('', 'admin_show');
      Route::put('', 'admin_update');
      Route::get('', 'admin_destroy');
    });
  });

  //Student users route
  Route::group(['prefix' => 'students'], function () {
    Route::get('', 'student_list');
    Route::post('', 'student_store');
    Route::group(['prefix' => '{student}'], function () {
      Route::get('', 'student_show');
      Route::put('', 'student_update');
      Route::get('', 'student_destroy');
    });
  });

  //Parent users route
  Route::group(['prefix' => 'guardians'], function () {
    Route::get('', 'guardian_list');
    Route::post('', 'guardian_store');
    Route::group(['prefix' => '{guardian}'], function () {
      Route::get('', 'guardian_show');
      Route::put('', 'guardian_update');
      Route::get('', 'guardian_destroy');
    });
  });

  //Teacher users route
  Route::group(['prefix' => 'teachers'], function () {
    Route::get('', 'teacher_list');
    Route::post('', 'teacher_store');
    Route::group(['prefix' => '{teacher}'], function () {
      Route::get('', 'teacher_show');
      Route::put('', 'teacher_update');
      Route::get('', 'teacher_destroy');
    });
  });

  //Routine routes
  Route::group(['prefix' => 'routines'], function () {
    Route::get('', 'routine_list');
    Route::post('', 'routine_store');
    Route::group(['prefix' => '{routine}'], function () {
      Route::get('', 'routine_show');
      Route::put('', 'routine_update');
      Route::get('', 'routine_destroy');
    });
  });

  //School route
  // Route::get('admin/school/info', 'school_edit')->name('admin.school.info');
  // Route::post('admin/school/update', 'school_update')->name('admin.school.update');

  //Marks route
  Route::group(['prefix' => 'marks'], function () {
    Route::get('', 'marks_list');
    Route::post('', 'marks_store');
    Route::group(['prefix' => '{marks}'], function () {
      Route::get('', 'marks_show');
      Route::put('', 'marks_update');
      Route::get('', 'marks_destroy');
    });
  });

  //Exam route
  Route::group(['prefix' => 'exams'], function () {
    Route::get('', 'exam_list');
    Route::post('', 'exam_store');
    Route::group(['prefix' => '{exam}'], function () {
      Route::get('', 'exam_show');
      Route::put('', 'exam_update');
      Route::get('', 'exam_destroy');
    });
  });

  //Grade routes
  Route::group(['prefix' => 'grades'], function () {
    Route::get('', 'grade_list');
    Route::post('', 'grade_store');
    Route::group(['prefix' => '{grade}'], function () {
      Route::get('', 'grade_show');
      Route::put('', 'grade_update');
      Route::get('', 'grade_destroy');
    });
  });

  //Subject routes
  Route::group(['prefix' => 'subjects'], function () {
    Route::get('', 'subject_list');
    Route::post('', 'subject_store');
    Route::group(['prefix' => '{subject}'], function () {
      Route::get('', 'subject_show');
      Route::put('', 'subject_update');
      Route::get('', 'subject_destroy');
    });
  });

  //Syllabus routes
  Route::group(['prefix' => 'syllabuses'], function () {
    Route::get('', 'syllabus_list');
    Route::post('', 'syllabus_store');
    Route::group(['prefix' => '{syllabus}'], function () {
      Route::get('', 'syllabus_show');
      Route::put('', 'syllabus_update');
      Route::get('', 'syllabus_destroy');
    });
  });

  //Section
  // Route::get('admin/section/edit/{id}', 'edit_section')->name('admin.edit.section');
  // Route::post('admin/section/update/{id}', 'section_update')->name('admin.update.section');

  //Class list routes
  Route::group(['prefix' => 'classes'], function () {
    Route::get('', 'classes_list');
    Route::post('', 'classes_store');
    Route::group(['prefix' => '{classes}'], function () {
      Route::get('', 'classes_show');
      Route::put('', 'classes_update');
      Route::get('', 'classes_destroy');
    });
  });

  //Profile
  // Route::get('admin/profile', 'profile')->name('admin.profile');
  // Route::get('admin/profile/edit/{id}', 'profile_edit')->name('admin.profile.edit');
  // Route::post('admin/profile/update/{id}', 'profile_update')->name('admin.profile.update');
  // Route::any('admin/password/{action_type}', 'password')->name('admin.password');
});
//Admin routes end here