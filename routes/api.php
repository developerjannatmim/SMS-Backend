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
  // ->name('admin.admin');
  // Route::get('admin/admin/create', 'admin_create')->name('admin.admin.create');
  // ->name('admin.admin.store');
  // ->name('admin.admin.edit');
  // ->name('admin.admin.update');
  // ->name('admin.admin.delete');

  //Student users route
  // Route::get('admin/student', 'student_list')->name('admin.student');
  // Route::get('admin/student/create', 'student_create')->name('admin.student.create');
  // Route::post('admin/student', 'student_store')->name('admin.student.store');
  // Route::get('admin/student/edit/{id}', 'student_edit')->name('admin.student.edit');
  // Route::post('admin/student/{id}', 'student_update')->name('admin.student.update');
  // Route::get('admin/student/delete/{id}', 'student_destroy')->name('admin.student.delete');

  //Parent users route
  // Route::get('admin/guardian', 'guardian_list')->name('admin.guardian');
  // Route::get('admin/guardian/create', 'guardian_create')->name('admin.guardian.create');
  // Route::post('admin/guardian', 'guardian_store')->name('admin.guardian.store');
  // Route::get('admin/guardian/edit/{id}', 'guardian_edit')->name('admin.guardian.edit');
  // Route::post('admin/guardian/{id}', 'guardian_update')->name('admin.guardian.update');
  // Route::get('admin/guardian/delete/{id}', 'guardian_destroy')->name('admin.guardian.delete');

  //Teacher users route
  // Route::get('admin/teacher', 'teacher_list')->name('admin.teacher');
  // Route::get('admin/teacher/create', 'teacher_create')->name('admin.teacher.create');
  // Route::post('admin/teacher', 'teacher_store')->name('admin.teacher.store');
  // Route::get('admin/teacher/edit/{id}', 'teacher_edit')->name('admin.teacher.edit');
  // Route::post('admin/teacher/{id}', 'teacher_update')->name('admin.teacher.update');
  // Route::get('admin/teacher/delete/{id}', 'teacher_destroy')->name('admin.teacher.delete');

  //Routine routes
  // Route::get('admin/routine', 'routine')->name('admin.routine');
  // Route::get('admin/routine/create', 'create_routine')->name('admin.routine.create');
  // Route::post('admin/routine/', 'store_routine')->name('admin.routine.store');
  // Route::get('admin/routine/edit/{id}', 'edit_routine')->name('admin.routine.edit');
  // Route::post('admin/routine/update/{id}', 'update_routine')->name('admin.routine.update');
  // Route::get('admin/routine/delete/{id}', 'routine_destroy')->name('admin.routine.delete');

  //School route
  // Route::get('admin/school/info', 'school_edit')->name('admin.school.info');
  // Route::post('admin/school/update', 'school_update')->name('admin.school.update');

  //Marks route
  // Route::get('admin/marks', 'marks')->name('admin.marks');
  // Route::get('admin/marks/create', 'create_marks')->name('admin.marks.create');
  // Route::post('admin/marks/', 'store_marks')->name('admin.marks.store');
  // Route::get('admin/marks/edit/{id}', 'edit_marks')->name('admin.marks.edit');
  // Route::post('admin/marks/update/{id}', 'update_marks')->name('admin.marks.update');
  // Route::get('admin/marks/delete/{id}', 'marks_destroy')->name('admin.marks.delete');

  //Exam route
  // Route::get('admin/exam', 'examList')->name('admin.exam');
  // Route::get('admin/exam_create', 'createExam')->name('admin.exam.create');
  // Route::post('admin/exam', 'examStore')->name('admin.store.exam');
  // Route::get('admin/exam/{id}', 'editExam')->name('admin.edit.exam');
  // Route::post('admin/exam/{id}', 'examUpdate')->name('admin.exam.update');
  // Route::get('admin/exam/delete/{id}', 'examDelete')->name('admin.exam.delete');
  // Route::get('admin/exam_list_by_class/{id}', 'classWiseExam')->name('admin.class_wise_exam_list');

  //Grade routes
  // Route::get('admin/grade', 'gradeList')->name('admin.grade');
  // Route::get('admin/grade_create', 'createGrade')->name('admin.grade.create');
  // Route::post('admin/grade', 'gradeStore')->name('admin.store.grade');
  // Route::get('admin/grade/{id}', 'editGrade')->name('admin.edit.grade');
  // Route::post('admin/grade/{id}', 'gradeUpdate')->name('admin.grade.update');
  // Route::get('admin/grade/delete/{id}', 'gradeDelete')->name('admin.grade.delete');

  //Subject routes
  // Route::get('admin/subject', 'subject_list')->name('admin.subject');
  // Route::get('admin/subject/create', 'create_subject')->name('admin.subject.create');
  // Route::post('admin/subject', 'subject_store')->name('admin.store.subject');
  // Route::get('admin/subject/{id}', 'edit_subject')->name('admin.edit.subject');
  // Route::post('admin/subject/{id}', 'subject_update')->name('admin.subject.update');
  // Route::get('admin/subject/delete/{id}', 'subject_destory')->name('admin.subject.delete');

  //Syllabus routes
  // Route::get('admin/syllabus', 'syllabus')->name('admin.syllabus');
  // Route::get('admin/syllabus/create', 'create_syllabus')->name('admin.syllabus.create');
  // Route::post('admin/syllabus/', 'store_syllabus')->name('admin.syllabus.store');
  // Route::get('admin/syllabus/edit/{id}', 'edit_syllabus')->name('admin.syllabus.edit');
  // Route::post('admin/syllabus/update/{id}', 'update_syllabus')->name('admin.syllabus.update');
  // Route::get('admin/syllabus/delete/{id}', 'syllabus_destroy')->name('admin.syllabus.delete');

  //Section
  // Route::get('admin/section/edit/{id}', 'edit_section')->name('admin.edit.section');
  // Route::post('admin/section/update/{id}', 'section_update')->name('admin.update.section');

  //Class list routes
  // Route::get('admin/class', 'class_list')->name('admin.class');
  // Route::get('admin/class/create', 'create_class')->name('admin.class.create');
  // Route::post('admin/class', 'class_store')->name('admin.store.class');
  // Route::get('admin/class/{id}', 'edit_class')->name('admin.edit.class');
  // Route::post('admin/class/{id}', 'class_update')->name('admin.class.update');
  // Route::get('admin/class/delete/{id}', 'class_destory')->name('admin.class.delete');

  //Profile
  // Route::get('admin/profile', 'profile')->name('admin.profile');
  // Route::get('admin/profile/edit/{id}', 'profile_edit')->name('admin.profile.edit');
  // Route::post('admin/profile/update/{id}', 'profile_update')->name('admin.profile.update');
  // Route::any('admin/password/{action_type}', 'password')->name('admin.password');
});
//Admin routes end here