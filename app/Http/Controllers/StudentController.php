<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;
use App\Models\Student;
use App\Models\Category;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $students = Student::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $classes = StudentClass::with('students')->where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();
        $classList = StudentClass::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();
        $category = Category::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('setting.student.index', compact('category', 'classes', 'notifications', 'students', 'classList'));
        // dd($classes);
    }

    public function add()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $students = Student::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();
        $class = StudentClass::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();
        $category = Category::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('setting.student.add', compact('notifications', 'students', 'class', 'category'));
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
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
            $student->year_id = $activeYearId;
            $student->user_id = Auth::user()->id;
            $student->save();
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

    public function updateAllClass(Request $request, $id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        Student::where('class_id', $id)
            ->where('year_id', $activeYearId)
            ->update([
                'class_id' => $request->input('class_id')
            ]);

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201); // 201 updated
    }

    public function destroyAllStudent($id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        Student::where('class_id', $id)
            ->where('year_id', $activeYearId)
            ->delete();

        return response()->json([
            'message' => 'Data updated successfully'
        ], 201); // 201 updated
    }
}
