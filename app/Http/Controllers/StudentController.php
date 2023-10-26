<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;
use App\Models\Student;
use App\Models\Category;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $studentClasses = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('setting.student.index', compact('notifications', 'studentClasses'));
    }

    public function detail($id)
    {
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = User::where('class_id', $id)->get();
        $class = StudentClass::find($id);
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();

        return view('setting.student.detail', compact('notifications', 'students', 'class', 'classes'));
    }

    public function updateAll(Request $request, $id)
    {
        $students = User::where('class_id', $id);
        $students->update(['class_id' => $request->input('class_id')]);

        $activeYearId = Year::where('year_status', 'active')->value('id');

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data siswa pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $students,
        ], 201);
    }

    public function add()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $students = Student::orderBy("updated_at", "DESC")->get();
        $class = StudentClass::orderBy("updated_at", "DESC")->get();
        $category = Category::orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('setting.student.add', compact('notifications', 'students', 'class', 'category'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required',
            'students' => 'required|array', // Array of students
            'students.*.nis' => 'required|string',
            'students.*.category_id' => 'required',
            'students.*.student_name' => 'required|string',
        ]);

        foreach ($data['students'] as $studentData) {
            $student = new Student();
            $student->class_id = $data['class_id'];
            $student->nis = $studentData['nis'];
            $student->student_name = $studentData['student_name'];
            $student->category_id = $studentData['category_id'];
            $student->user_id = Auth::user()->id;
            $student->save();

            // Dapatkan id_credit yang sesuai dari tabel category_has_credit
            $category = Category::findOrFail($studentData['category_id']);
            $creditIds = $category->credits->pluck('id')->toArray();

            // Simpan relasi antara siswa dan kredit dalam UserHasCredit
            $student->credits()->sync($creditIds);
        }
        return response()->json([
            'message' => 'Data inserted successfully'
        ], 201);
    }

    // Student Delete Data
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Data Siswa tidak ditemukan.'], 404);
        }

        $student->delete();

        // Create Notification Year Data Delete
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Siswa" . " " . $student->student_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
    // Student Delete Data

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        $student->update([
            "student_name" =>  $request->input('student_name'),
            "nis" =>  $request->input('nis'),
            "class_id" =>  $request->input('class_id'),
            "category_id" =>  $request->input('category_id'),
            'user_id' => Auth::user()->id
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Siswa" . " " . $student->student_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $student,
        ], 201); // 201 updated
    }

    public function updatePartialClass(Request $request)
    {
        $classId = $request->input('class_id');
        $selectedUsers = $request->input('selected_users', []);

        // Perbarui entitas User berdasarkan checkbox yang dipilih
        User::whereIn('id', $selectedUsers)->update(['class_id' => $classId]);

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201); // 201 updated
    }

    public function updateAllClass(Request $request, $id)
    {
        $users = User::where('class_id', $id)->get();
        foreach ($users as $user) {
            $user->class_id = $request->input('class_id');
            $user->save();
        }

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201); // 201 updated
    }

    public function destroyAllStudent($id)
    {
        User::where('class_id', $id)
            ->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ], 201);
    }
}
