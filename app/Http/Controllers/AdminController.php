<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Routine;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class AdminController extends Controller
{
  public function adminDashboard()
  {
    if (auth()->user()->role_id != '') {
      return view('admin.dashboard');
    } else {
      redirect()->route('login')
        ->with('error', 'You are not logged in.');
    }
  }

  public function profile()
  {
    return view('admin.profile.view');
  }

  public function profile_edit(string $id)
  {
    $admin = User::find($id);
    return view('admin.profile.update', compact('admin'));
  }

  public function profile_update(Request $request, string $id)
  {
    $data = $request->all();

    if (!empty($data['photo'])) {
      $file = $data['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('admin-images/', $filename);
      $photo = $filename;
    } else {
      $user_info = User::where('id', $id)->value('user_information');
      $exsisting_filename = json_decode($user_info)->photo;
      if ($exsisting_filename !== '') {
        $photo = $exsisting_filename;
      } else {
        $photo = '';
      }
    }
    $user_info = User::where('id', $id)->value('user_information');

    $info = array(
      'gender' => $data['gender'],
      'blood_group' => json_decode($user_info)->blood_group,
      'birthday' => date($data['birthday']),
      'phone' => $data['phone'],
      'address' => $data['address'],
      'photo' => $photo
    );

    $data['user_information'] = json_encode($info);
    User::where('id', $id)->update([
      'name' => $data['name'],
      'email' => $data['email'],
      'user_information' => $data['user_information']
    ]);
    return redirect()->route('admin.admin')->with('success', 'Profile Updated Successfully');
  }


  //Student
  public function student_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $students = User::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 3);
      })->orWhere(function ($query) use($search) {
        $query->where('email', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 3);
      })->paginate(5);

    }else {
      $students = User::get()->where('role_id', 3)->where('school_id', auth()->user()->school_id);
    }
    return view('admin.student.student_list', ['students' => $students, 'search' => $search]);
  }

  public function student_create()
  {
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    return view('admin.student.add_student', ['classes' => $classes, 'sections' => $sections]);
  }

  public function student_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'class_id' => 'required',
      'section_id' => 'required',
      'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
      'gender' => 'required',
      'blood_group' => 'required',
      'birthday' => 'required',
      'phone' => 'required',
      'address' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'password.required' => 'Password field is required.',
      'email.required' => 'Email field is required.',
      'email.email' => 'Email field must be email address.'
  ]);

  $validated['password'] = bcrypt($validated['password']);

    if (!empty($validated['photo'])) {
      $file = $validated['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('students-images/', $filename);
      $photo = $filename;
    } else {
      $photo = '';
    }

    $info = array(
      'gender' => $validated['gender'],
      'blood_group' => $validated['blood_group'],
      'birthday' => date($validated['birthday']),
      'phone' => $validated['phone'],
      'address' => $validated['address'],
      'photo' => $photo
    );

    $validated['user_information'] = json_encode($info);

    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => $validated['password'],
      'class_id' => $validated['class_id'],
      'section_id' => $validated['section_id'],
      'role_id' => '3',
      'school_id' => auth()->user()->school_id,
      'user_information' => $validated['user_information']
    ]);

    return redirect()->route('admin.student')->with('success', 'Student Added Successfully');
  }

  public function student_edit(string $id)
  {
    $student = User::find($id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    return view('admin.student.edit_student', ['classes' => $classes, 'sections' => $sections, 'student' => $student]);
  }

  public function student_update(Request $request, string $id)
  {
    $data = $request->all();

    if (!empty($data['photo'])) {
      $file = $data['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('/images/student/', $filename);
      $photo = $filename;
    } else {
      $user_info = User::where('id', $id)->value('user_information');
      $exsisting_filename = json_decode($user_info)->photo;
      if ($exsisting_filename !== '') {
        $photo = $exsisting_filename;
      } else {
        $photo = '';
      }
    }

    $info = array(
      'gender' => $data['gender'],
      'blood_group' => $data['blood_group'],
      'birthday' => date($data['birthday']),
      'phone' => $data['phone'],
      'address' => $data['address'],
      'photo' => $photo
    );

    $data['user_information'] = json_encode($info);
    User::where('id', $id)->update([
      'name' => $data['name'],
      'email' => $data['email'],
      'section_id' => $data['section_id'],
      'class_id' => $data['class_id'],
      'user_information' => $data['user_information']
    ]);
    return redirect()->route('admin.student')->with('success', 'Student Updated Successfully');
  }

  public function student_destroy(string $id)
  {
    $student = User::find($id);
    $student->delete();
    $student = User::get()->where('role_id', 3)->where('school_id', auth()->user()->school_id);
    return redirect()->back();
  }

  //Guardian

  public function guardian_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $parents = User::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 4);
      })->orWhere(function ($query) use($search) {
        $query->where('email', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 4);
      })->paginate(5);

    }else {
      $parents = User::get()->where('role_id', 4)->where('school_id', auth()->user()->school_id);
    }
    return view('admin.parent.parent_list', ['parents' =>$parents, 'search' => $search]);

  }

  public function guardian_create()
  {
    $parents = User::get()->where('role_id', 4)->where('school_id', auth()->user()->school_id);
    return view('admin.parent.add_parent', compact('parents'));

  }

  public function guardian_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
      'gender' => 'required',
      'blood_group' => 'required',
      'birthday' => 'required',
      'phone' => 'required',
      'address' => 'required',
      'child_name' => 'required',
      'designation' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'password.required' => 'Password field is required.',
      'email.required' => 'Email field is required.',
      'email.email' => 'Email field must be email address.'
  ]);

  $validated['password'] = bcrypt($validated['password']);

    if (!empty($validated['photo'])) {
      $file = $validated['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('parent-images/', $filename);
      $photo = $filename;
    } else {
      $photo = '';
    }

    $info = array(
      'gender' => $validated['gender'],
      'blood_group' => $validated['blood_group'],
      'birthday' => date($validated['birthday']),
      'phone' => $validated['phone'],
      'address' => $validated['address'],
      'child_name' => $validated['child_name'],
      'designation' => $validated['designation'],
      'photo' => $photo
    );

    $validated['user_information'] = json_encode($info);
    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => $validated['password'],
      'role_id' => '4',
      'school_id' => auth()->user()->school_id,
      'user_information' => $validated['user_information']
    ]);
    return redirect()->route('admin.guardian')->with('success', 'Parent Added Successfully');

  }

  public function guardian_edit(string $id)
  {
    $parent = User::find($id);
    return view('admin.parent.edit_parent', compact('parent'));
  }

  public function guardian_update(Request $request, string $id)
  {
    $data = $request->all();
    if (!empty($data['photo'])) {
      $file = $data['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('parent-images/', $filename);
      $photo = $filename;
    } else {
      $user_info = User::where('id', $id)->value('user_information');
      $exsisting_filename = json_decode($user_info)->photo;
      if ($exsisting_filename !== '') {
        $photo = $exsisting_filename;
      } else {
        $photo = '';
      }
    }

    $info = array(
      'gender' => $data['gender'],
      'blood_group' => $data['blood_group'],
      'birthday' => date($data['birthday']),
      'phone' => $data['phone'],
      'address' => $data['address'],
      'child_name' => $data['child_name'],
      'designation' => $data['designation'],
      'photo' => $photo
    );

    $data['user_information'] = json_encode($info);
    User::where('id', $id)->update([
      'name' => $data['name'],
      'email' => $data['email'],
      'user_information' => $data['user_information']
    ]);
    return redirect()->route('admin.guardian')->with('success', 'Parent Updated Successfully');
  }

  public function guardian_destroy(string $id)
  {
    $parent = User::find($id);
    $parent->delete();
    $parent = User::get()->where('role_id', 4)->where('school_id', auth()->user()->school_id);
    return redirect()->back();
  }

  //Teacher

  public function teacher_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $teachers = User::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 2);
      })->orWhere(function ($query) use($search) {
        $query->where('email', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 2);
      })->paginate(5);

    }else {
      $teachers = User::get()->where('role_id', 2)->where('school_id', auth()->user()->school_id);
    }
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    return view('admin.teacher.teacher_list', compact('teachers', 'classes', 'search'));

  }

  public function teacher_create()
  {
    $teachers = User::get()->where('role_id', 2)->where('school_id', auth()->user()->school_id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    return view('admin.teacher.add_teacher', compact('teachers', 'classes'));

  }

  public function teacher_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
      'gender' => 'required',
      'blood_group' => 'required',
      'birthday' => 'required',
      'phone' => 'required',
      'address' => 'required',
      'designation' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'password.required' => 'Password field is required.',
      'email.required' => 'Email field is required.',
      'email.email' => 'Email field must be email address.'
  ]);

    if (!empty($validated['photo'])) {
      $file = $validated['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('teacher-images/', $filename);
      $photo = $filename;
    } else {
      $photo = '';
    }

    $info = array(
      'gender' => $validated['gender'],
      'blood_group' => $validated['blood_group'],
      'birthday' => $validated['birthday'],
      'phone' => $validated['phone'],
      'address' => $validated['address'],
      'designation' => $validated['designation'],
      'photo' => $photo
    );

    $validated['user_information'] = json_encode($info);
    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => $validated['password'],
      'role_id' => '2',
      'school_id' => auth()->user()->school_id,
      'user_information' => $validated['user_information']
    ]);
    return redirect()->route('admin.teacher')->with('success', 'Teacher Added Successfully');

  }

  public function teacher_edit(string $id)
  {
    $teacher = User::find($id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    return view('admin.teacher.edit_teacher', compact('teacher', 'subjects'));
  }

  public function teacher_update(Request $request, string $id)
  {
    $data = $request->all();
    if (!empty($data['photo'])) {
      $file = $data['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('teacher-images/', $filename);
      $photo = $filename;
    } else {
      $user_info = User::where('id', $id)->value('user_information');
      $exsisting_filename = json_decode($user_info)->photo;
      if ($exsisting_filename !== '') {
        $photo = $exsisting_filename;
      } else {
        $photo = '';
      }
    }

    $info = array(
      'gender' => $data['gender'],
      'blood_group' => $data['blood_group'],
      'birthday' => $data['birthday'],
      'phone' => $data['phone'],
      'address' => $data['address'],
      'designation' => $data['designation'],
      'photo' => $photo
    );

    $data['user_information'] = json_encode($info);
    User::where('id', $id)->update([
      'name' => $data['name'],
      'email' => $data['email'],
      'user_information' => $data['user_information']
    ]);
    return redirect()->route('admin.teacher')->with('success', 'Teacher Updated Successfully');
  }

  public function teacher_destroy(string $id)
  {
    $teacher = User::find($id);
    $teacher->delete();
    $teacher = User::get()->where('role_id', 2)->where('school_id', auth()->user()->school_id);
    return redirect()->back();
  }

  //Admin

  public function admin_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $admins = User::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 1);
      })->orWhere(function ($query) use($search) {
        $query->where('email', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id)
        ->where('role_id', 1);
      })->paginate(5);

    }else {
      $admins = User::where('role_id', 1)->where('school_id', auth()->user()->school_id)->paginate(5);
    }

    return view('admin.admin.admin_list', ['admins'=> $admins, 'search' => $search]);

  }

  public function admin_create()
  {
    $admins = User::get()->where('role_id', 1)->where('school_id', auth()->user()->school_id);
    return view('admin.admin.add_admin', compact('admins'));

  }

  public function admin_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
      'gender' => 'required',
      'blood_group' => 'required',
      'birthday' => 'required',
      'phone' => 'required',
      'address' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'password.required' => 'Password field is required.',
      'email.required' => 'Email field is required.',
      'email.email' => 'Email field must be email address.'
  ]);

    if (!empty($validated['photo'])) {
      $file = $validated['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('admin-images/', $filename);
      $photo = $filename;
    } else {
      $photo = '';
    }

    $info = array(
      'gender' => $validated['gender'],
      'blood_group' => $validated['blood_group'],
      'birthday' => date($validated['birthday']),
      'phone' => $validated['phone'],
      'address' => $validated['address'],
      'photo' => $photo
    );

    $validated['user_information'] = json_encode($info);
    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => $validated['password'],
      'role_id' => '1',
      'school_id' => auth()->user()->school_id,
      'user_information' => $validated['user_information']
    ]);
    return redirect()->route('admin.admin')->with('success', 'Admin Added Successfully');

  }

  public function admin_edit(string $id)
  {
    $admin = User::find($id);
    return view('admin.admin.edit_admin', compact('admin'));
  }

  public function admin_update(Request $request, string $id)
  {
    $data = $request->all();

    if (!empty($data['photo'])) {
      $file = $data['photo'];
      $filename = time() . '-' . $file->getClientOriginalExtension();
      $file->move('admin-images/', $filename);
      $photo = $filename;
    } else {
      $user_info = User::where('id', $id)->value('user_information');
      $exsisting_filename = json_decode($user_info)->photo;
      if ($exsisting_filename !== '') {
        $photo = $exsisting_filename;
      } else {
        $photo = '';
      }
    }

    $info = array(
      'gender' => $data['gender'],
      'blood_group' => $data['blood_group'],
      'birthday' => date($data['birthday']),
      'phone' => $data['phone'],
      'address' => $data['address'],
      'photo' => $photo
    );

    $data['user_information'] = json_encode($info);
    User::where('id', $id)->update([
      'name' => $data['name'],
      'email' => $data['email'],
      'user_information' => $data['user_information']
    ]);
    return redirect()->route('admin.admin')->with('success', 'Admin Updated Successfully');
  }

  public function admin_destroy(string $id)
  {
    $admin = User::find($id);
    $admin->delete();
    $admin = User::get()->where('role_id', 1)->where('school_id', auth()->user()->school_id);
    return redirect()->back();
  }

  //School
  public function school_edit()
  {
    $school = auth()->user()->school;
    return view('admin.settings.school_settings', ['school' => $school]);
  }

  public function school_update(Request $request)
  {
    $data = $request->only('title', 'email', 'phone', 'address', 'school_info', 'status');

    School::where('id', auth()->user()->school_id)->update($data);

    return redirect()->back()->with('success', 'School updated Successfully');
  }

  //Grades
  public function gradeList()
  {
    $grades = Grade::get()->where('school_id', auth()->user()->school_id);
    return view('admin.grade.grade_list', ['grades' => $grades]);
  }

  public function createGrade()
  {
    return view('admin.grade.add_grade');
  }

  public function gradeStore(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'grade_point' => 'required',
      'mark_from' => 'required',
      'mark_upto' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'grade_point.required' => 'Grade Point field is required.',
      'mark_from.required' => 'Mark From field is required.',
      'mark_upto.required' => 'Mark Upto field is required.'
  ]);

    $duplicate_grade_check = Grade::get()->where('name', $validated['grade'])->where('school_id', auth()->user()->school_id);

    if (count($duplicate_grade_check) == 0) {
      Grade::create([
        'name' => $validated['grade'],
        'grade_point' => $validated['grade_point'],
        'mark_from' => $validated['mark_from'],
        'mark_upto' => $validated['mark_upto'],
        'school_id' => auth()->user()->school_id,
      ]);

      return redirect()->back()->with(['error' => 'Sorry this grade already exists']);

    } else {
      return view('admin.grade.grade_list')->with(['message' => 'You have successfully create a new grade.']);
    }
  }

  public function editGrade(string $id)
  {
    $grade = Grade::find($id);
    return view('admin.grade.edit_grade', ['grade' => $grade]);
  }

  public function gradeUpdate(Request $request, $id)
  {
    $data = $request->all();
    Grade::where('id', $id)->update([
      'name' => $data['grade'],
      'grade_point' => $data['grade_point'],
      'mark_from' => $data['mark_from'],
      'mark_upto' => $data['mark_upto'],
      'school_id' => auth()->user()->school_id,
    ]);

    return redirect()->back()->with('message', 'You have successfully update grade.');
  }

  public function gradeDelete($id)
  {
    $grade = Grade::find($id);
    $grade->delete();
    $grade = Grade::get()->where('school_id', auth()->user()->school_id);
    return redirect()->back()->with('message', 'You have successfully delete grade.');
  }

  //Subject
  public function subject_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $subjects = Subject::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id);
      })->paginate(5);

    }else {
      $subjects = Subject::where('school_id', auth()->user()->school_id)->paginate(5);
    }
    return view('admin.subject.subject_list', ['subjects' => $subjects, 'search' => $search]);
  }

  public function create_subject()
  {
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    return view('admin.subject.add_subject', ['classes' => $classes]);
  }

  public function subject_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'class_id' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'class_id.required' => 'Class Id field is required.'
  ]);

    Subject::create([
      'name' => $validated['name'],
      'class_id' => $validated['class_id'],
      'school_id' => auth()->user()->school_id,
    ]);
    return redirect()->route('admin.subject')->with(['message' => 'You have successfully create a new Subject.']);
  }

  public function edit_subject(string $id)
  {
    $subject = Subject::find($id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    return view('admin.subject.edit_subject', ['subject' => $subject, 'classes' => $classes]);
  }

  public function subject_update(Request $request, $id)
  {
    $data = $request->all();

    Subject::where('id', $id)->update([
      'name' => $data['name'],
      'class_id' => $data['class_id'],
      'school_id' => auth()->user()->school_id,
    ]);

    return redirect()->route('admin.subject')->with('message', 'You have successfully update Subject.');
  }

  public function subject_destory($id)
  {
    $subject = Subject::find($id);
    $subject->delete();
    $subject = Subject::get()->where('school_id', auth()->user()->school_id);
    return redirect()->back()->with('message', 'You have successfully delete Subject.');
  }

  //Class
  public function class_list(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){

      $classes = Classes::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id);
      })->paginate(5);

    }else {
      $classes = Classes::where('school_id', auth()->user()->school_id)->paginate(5);
      }
      $sections = Section::get()->where('school_id', auth()->user()->school_id);

    return view('admin.class.class_list', ['sections' => $sections, 'classes' => $classes, 'search' => $search]);
  }

  public function create_class()
  {
    return view('admin.class.add_class');
  }

  public function class_store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required'
    ],[
      'name.required' => 'Name field is required.'
  ]);

    $duplicate_class_name = Classes::get()->where('name', $validated['name'])->where('school_id', auth()->user()->school_id);

    if(count($duplicate_class_name) == 0){
      $id = Classes::create([
        'name' => $validated['name'],
        'school_id' => auth()->user()->school_id
      ])->id;

      Section::create([
        'name' => 'A',
        'class_id' => $id,
        'school_id' => auth()->user()->school_id
      ]);

      return redirect()->route('admin.class')->with(['success' => 'You have successfully create a new class.']);
    }else {
      return redirect()->back()->with('error', 'sorry already the class name is exisist');
    }

  }

  //Section
  public function edit_section(string $id)
  {
    $section = Section::find($id);
    return view('admin.class.edit_section', compact('section'));
  }

  public function section_update(Request $request, string $id)
  {
    $data = $request->all();

    Section::where('id', $id)->update([
      'name' => $data['name'],
      'school_id' => auth()->user()->school_id
    ]);
    return redirect()->route('admin.class');
  }
 //Section end
  public function edit_class(string $id)
  {
    $class = Classes::find($id);
    return view('admin.class.edit_class', ['class' => $class]);
  }

  public function class_update(Request $request, $id)
  {
    $data = $request->all();

    $duplicate_class_name = Classes::get()->where('name', $data['name'])->where('school_id', auth()->user()->school_id);

    if( count($duplicate_class_name) == 0 ){
      Classes::where('id', $id)->update([
        'name' => $data['name'],
        'school_id' => auth()->user()->school_id,
      ]);
      return redirect()->route('admin.class')->with('message', 'You have successfully update class.');
    }
    return redirect()->back()->with('error', 'sorry already the class name is exisist');
  }

  public function class_destory($id)
  {
    // $class = Classes::find($id);
    // $class->delete();
    // return redirect()->back()->with('message', 'You have successfully delete class.');
  }

  //Exam
  public function examList(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $exams = Exam::where(function ($query) use($search) {
        $query->where('name', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id);
      })->paginate(5);

    }else {
      $exams = Exam::where('school_id', auth()->user()->school_id)->paginate(5);
    }
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    return view('admin.examination.exam_list', ['exams' => $exams, 'classes' => $classes, 'search' => $search]);
  }

  public function createExam()
  {
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    return view('admin.examination.add_exam', ['classes' => $classes, 'sections' => $sections]);
  }

  public function examStore(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'exam_type' => 'required',
      'starting_time' => 'required',
      'ending_time' => 'required',
      'total_marks' => 'required',
      'class_id' => 'required',
      'section_id' => 'required'
    ],[
      'name.required' => 'Name field is required.',
      'class_id.required' => 'Class Id field is required.',
      'exam_type.required' => 'exam_type field is required.',
      'starting_time.required' => 'starting_time field is required.',
      'ending_time.required' => 'ending_time field is required.',
      'total_marks.required' => 'total_marks field is required.',
      'section_id.required' => 'section_id field is required.',
    ]);

    Exam::create([
      'name' => $validated['exam_name'],
      'exam_type' => $validated['exam_type'],
      'starting_time' => $validated['starting_time'],
      'ending_time' => $validated['ending_time'],
      'total_marks' => $validated['total_marks'],
      'status' => 'pending',
      'class_id' => $validated['class_id'],
      'section_id' => $validated['section_id'],
      'school_id' => auth()->user()->school_id,
    ]);

    return redirect()->route('admin.exam')->with('success', 'You have successfully create a new exam.');
  }

  public function editExam(string $id)
  {
    $exam = Exam::find($id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', $exam->school_id);
    return view('admin.examination.edit_exam', ['exam' => $exam, 'classes' => $classes, 'sections' => $sections]);

  }

  public function examUpdate(Request $request, $id)
  {
    $data = $request->all();
    Exam::where('id', $id)->update([
      'name' => $data['exam_name'],
      'exam_type' => 'offline',
      'starting_time' => $data['starting_time'],
      'ending_time' => $data['ending_time'],
      'total_marks' => $data['total_marks'],
      'status' => 'pending',
      'class_id' => $data['class_id'],
      'section_id' => $data['section_id'],
      'school_id' => auth()->user()->school_id
    ]);
    return redirect()->back()->with('message', 'You have successfully update exam.');

  }

  public function examDelete($id)
  {
    $exam = Exam::find($id);
    $exam->delete();
    return redirect()->back()->with('message', 'You have successfully delete exam.');
  }

  public function classWiseExam($id)
  {
    $id = array('class_id');
    $exams = Exam::where([
      'class_id' => $id
    ])->first();
    $classes = Classes::where('school_id', auth()->user()->school_id)->get();
    return view('admin.examination.exam_list', ['exams' => $exams, 'classes' => $classes]);
  }

  //Marks
  public function marks()
  {
    $marks = Mark::get()->where('school_id', auth()->user()->school_id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);

    return view('admin.marks.marks_list', ['marks' => $marks, 'classes' => $classes, 'sections' => $sections, 'subjects' => $subjects]);
  }

  public function create_marks()
  {
    $students_name = User::get()->where('role_id', 3)->where('school_id', auth()->user()->school_id);
    $exams = Exam::get()->where('school_id', auth()->user()->school_id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    return view('admin.marks.add_mark', ['students_name' => $students_name, 'classes' => $classes, 'sections' => $sections, 'subjects' => $subjects, 'exams' => $exams]);
  }

  public function store_marks(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required',
      'exam_id' => 'required',
      'class_id' => 'required',
      'section_id' => 'required',
      'subject_id' => 'required',
      'marks' => 'required',
      'grade_point' => 'required',
      'comment' => 'required'
    ],[
      'user_id.required' => 'Name field is required.',
      'exam_id.required' => 'Exam Id field is required.',
      'class_id.required' => 'Class Id field is required.',
      'section_id.required' => 'section_id field is required.',
      'subject_id.required' => 'subject_id field is required.',
      'marks.required' => 'marks field is required.',
      'grade_point.required' => 'grade_point field is required.',
      'comment.required' => 'comment field is required.'
    ]);

    Mark::create([
      'user_id' => $validated['user_id'],
      'exam_id' => $validated['exam_id'],
      'class_id' => $validated['class_id'],
      'section_id' => $validated['section_id'],
      'subject_id' => $validated['subject_id'],
      'marks' => $validated['marks'],
      'grade_point' => $validated['grade_point'],
      'comment' => $validated['comment'],
      'school_id' => auth()->user()->school_id
    ]);

    return redirect()->route('admin.marks');
  }

  public function edit_marks(string $id)
  {
    $mark = Mark::find($id);
    $exams = Exam::get()->where('school_id', auth()->user()->school_id);
    $students_name = User::get()->where('role_id', 3)->where('school_id', auth()->user()->school_id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    return view('admin.marks.edit_mark', ['mark' => $mark, 'classes' => $classes, 'sections' => $sections, 'subjects' => $subjects, 'students_name' => $students_name, 'exams' => $exams]);
  }

  public function update_marks(Request $request, string $id)
  {
    $data = $request->all();

    Mark::where('id', $id)->update([
      'user_id' => $data['user_id'],
      'exam_id' => $data['exam_id'],
      'class_id' => $data['class_id'],
      'section_id' => $data['section_id'],
      'subject_id' => $data['subject_id'],
      'marks' => $data['marks'],
      'grade_point' => $data['grade_point'],
      'comment' => $data['comment'],
      'school_id' => auth()->user()->school_id
    ]);
    return redirect()->route('admin.marks');
  }

  public function marks_destroy(string $id)
  {
    $mark = Mark::find($id);
    $mark->delete();
    return redirect()->route('admin.marks');
  }

  //Routine
  public function routine()
  {
    return view('admin.routine.routine');
  }
  public function create_routine()
  {
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $class_rooms = ClassRoom::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    $routine_creator = User::where('role_id', 2)->where('school_id', auth()->user()->school_id)->get();
    return view('admin.routine.add_routine', ['classes' => $classes, 'sections' => $sections, 'class_rooms' => $class_rooms, 'subjects' => $subjects, 'routine_creator' => $routine_creator]);
  }

  public function store_routine(Request $request)
  {
    $validated = $request->validate([
      'class_id' => 'required',
      'section_id' => 'required',
      'subject_id' => 'required',
      'routine_creator' => 'required',
      'room_id' => 'required',
      'day' => 'required',
      'starting_hour' => 'required',
      'starting_minute' => 'required',
      'ending_hour' => 'required',
      'ending_minute' => 'required'
    ],[
      'class_id.required' => 'Class Id field is required.',
      'section_id.required' => 'section_id field is required.',
      'subject_id.required' => 'subject_id field is required.',
      'routine_creator.required' => 'routine_creator field is required.',
      'room_id.required' => 'room_id field is required.',
      'day.required' => 'day field is required.',
      'starting_hour.required' => 'starting_hour field is required.',
      'ending_hour.required' => 'ending_hour field is required.'
    ]);

    Routine::create([
      'class_id' => $validated['class_id'],
      'section_id' => $validated['section_id'],
      'subject_id' => $validated['subject_id'],
      'routine_creator' => $validated['routine_creator'],
      'room_id' => $validated['room_id'],
      'day' => $validated['day'],
      'starting_hour' => $validated['starting_hour'],
      'starting_minute' => $validated['starting_minute'],
      'ending_hour' => $validated['ending_hour'],
      'ending_minute' => $validated['ending_minute'],
      'school_id' => auth()->user()->school_id
    ]);

    return redirect()->route('admin.routine');
  }

  public function edit_routine(string $id)
  {
    $routine = Routine::find($id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $class_rooms = ClassRoom::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    $routine_creator = User::where('role_id', 2)->where('school_id', auth()->user()->school_id)->get();
    return view('admin.routine.edit_routine', ['routine' => $routine, 'classes' => $classes, 'sections' => $sections, 'class_rooms' => $class_rooms, 'subjects' => $subjects, 'routine_creator' => $routine_creator]);
  }

  public function update_routine(Request $request, string $id)
  {
    $data = $request->all();

    Routine::where('id', $id)->update([
      'class_id' => $data['class_id'],
      'section_id' => $data['section_id'],
      'subject_id' => $data['subject_id'],
      'routine_creator' => $data['routine_creator'],
      'room_id' => $data['room_id'],
      'day' => $data['day'],
      'starting_hour' => $data['starting_hour'],
      'starting_minute' => $data['starting_minute'],
      'ending_hour' => $data['ending_hour'],
      'ending_minute' => $data['ending_minute'],
      'school_id' => auth()->user()->school_id
    ]);

    return redirect()->route('admin.routine');
  }

  public function routine_destroy(string $id)
  {
    $routine = Routine::find($id);
    $routine->delete();
    return redirect()->route('admin.routine');
  }

  //Syllabus
  public function syllabus(Request $request)
  {
    $search = $request['search'] ?? '';

    if($search != ''){
      $syllabuses = Syllabus::where(function ($query) use($search) {
        $query->where('title', 'LIKE', "%{$search}%")
        ->where('school_id', auth()->user()->school_id);
      })->paginate(5);

    }else {
      $syllabuses = Syllabus::where('school_id', auth()->user()->school_id)->paginate(5);
    }

    return view('admin.syllabus.syllabus_list', ['syllabuses' => $syllabuses, 'search' => $search]);
  }

  public function create_syllabus()
  {
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    return view('admin.syllabus.add_syllabus', ['subjects' => $subjects, 'classes' => $classes, 'sections' => $sections]);
  }

  public function store_syllabus(Request $request)
  {
    $validated = $request->validate([
      'class_id' => 'required',
      'section_id' => 'required',
      'subject_id' => 'required',
      'title' => 'required',
      'image' => 'required'
    ],[
      'class_id.required' => 'Class Id field is required.',
      'section_id.required' => 'section_id field is required.',
      'subject_id.required' => 'subject_id field is required.',
      'title.required' => 'title field is required.',
      'image.required' => 'image field is required.'
    ]);

    if(!empty($validated['image'])){
      $file = $validated['image'];
      $filename = time(). '.'. $file->getClientOriginalExtension();
      $file->move('syllabus_images/', $filename);
      $image = $filename;
    }else {
      $image = '';
    }

    Syllabus::create([
      'title' => $validated['title'],
      'file' => $image,
      'subject_id' => $validated['subject_id'],
      'class_id' => $validated['class_id'],
      'section_id' => $validated['section_id'],
      'school_id' => auth()->user()->school_id
    ]);
    return redirect()->route('admin.syllabus');
  }

  public function edit_syllabus(string $id)
  {
    $syllabus = Syllabus::find($id);
    $classes = Classes::get()->where('school_id', auth()->user()->school_id);
    $sections = Section::get()->where('school_id', auth()->user()->school_id);
    $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
    return view('admin.syllabus.edit_syllabus', ['syllabus' => $syllabus, 'subjects' => $subjects, 'classes' => $classes, 'sections' => $sections]);
  }

  public function update_syllabus(Request $request, string $id)
  {
    $data = $request->all();

    if(!empty($data['image'])){
      $file = $data['image'];
      $filename = time(). '.'. $file->getClientOriginalExtension();
      $file->move('syllabus_images/', $filename);
      $image = $filename;
    }else {
      $exisisting_image = Syllabus::where('id', $id)->value('file');
      if($exisisting_image !== ''){
        $image = $exisisting_image;
      }else {
        $image = '';
      }
    }

    Syllabus::where('id', $id)->update([
      'title' => $data['title'],
      'file' => $image,
      'subject_id' => $data['subject_id'],
      'class_id' => $data['class_id'],
      'section_id' => $data['section_id'],
      'school_id' => auth()->user()->school_id
    ]);
    return redirect()->route('admin.syllabus');
  }

  public function syllabus_destroy(string $id)
  {
    $syllabus = Syllabus::find($id);
    $syllabus->delete();
    return redirect()->route('admin.syllabus');
  }


}