<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Score;
use App\Helpers\Helper;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\AssignmentRepository;

class AssignmentController extends Controller
{
    protected $assignment;

    public function __construct(AssignmentRepository $assignment)
    {
        $this->assignment = $assignment;
    }

    public function index()
    {
        $subjects = Subject::all();
        $classes = Classes::all();
        $lecturers = Lecturer::all();
        return view('assignment.index', compact('subjects', 'classes', 'lecturers'));
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'subject_id',
            2 => 'class_id',
            3 => 'lecturer_id',
            4 => 'semester',
            5 => 'created_at',
            6 => 'options'
        ];
        $searchWord = $request->input('searchWord');
        $subjectId = $request->input('subject_id');
        $classId = $request->input('class_id');
        $lecturerId = $request->input('lecturer_id');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $assignments = $this->assignment->findAllAssignment($searchWord, $subjectId, $classId, $lecturerId, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($assignments['data'])) {
            foreach ($assignments['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getAssignmentById/' . $id;
                $urlDelete = '/deleteAssignment/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = '';
                $row['index'] = ++$index + $start;
                $row['class_name'] = $item->class_id != '' ? Classes::find($item->class_id)->name : '---';
                $row['subject_name'] = $item->subject_id != '' ? Subject::find($item->subject_id)->name : '---';
                $row['lecturer_name'] = $item->lecturer_id != '' ? Lecturer::find($item->lecturer_id)->name : '---';
                $row['semester'] = $item->semester != '' ? ('Học kỳ ' . $item->semester) : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($assignments['recordsTotal']),
            "recordsFiltered" => intval($assignments['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'classes' => 'required',
            'lecturer_id' => 'required',
            'semester' => 'required',
            'subject_id' => ['required', Rule::unique('assignments')->where(function ($query) use ($request) {
                $check = false;
                foreach($request->classes as $classId) {
                    $check = $query->where('class_id', $classId);
                    if($check == true) return $check;
                }
                return $check;
            })],
            'lecturer_id' => ['required', Rule::unique('assignments')->where(function ($query) use ($request) {
                $check = false;
                foreach($request->classes as $classId) {
                    $check = $query->where('class_id', $classId);
                    if($check == true) return $check;
                }
                return $check;
            })],
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập.',
            'subject_id.unique' => 'Học phần và lớp đã tồn tại.'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            DB::beginTransaction();
            foreach($request->classes as $classId) {
                $this->assignment->create([
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'lecturer_id' => $request->lecturer_id,
                    'semester' => $request->semester
                ]);
                $students = Student::where('class_id', $classId)->get();
                foreach($students as $student)
                {
                    Score::create([
                        'subject_id' => $request->subject_id,
                        'student_id' => $student->id
                    ]);
                }
            }
            DB::commit();
            $jsonFormat['status']  = 1;
            $jsonFormat['message'] = 'Thêm thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $jsonFormat['message'] = $e->getMessage();
        }
        return response()->json($jsonFormat);
    }

    public function edit($id) {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        if ($this->assignment->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->assignment->find($id);
        } else {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
        }
        return response()->json($jsonFormat);
    }

    public function update(Request $request, $id)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'lecturer_id' => 'required',
            'semester' => 'required',
            'subject_id' => ['required', Rule::unique('assignments')->where(function ($query) use ($request) {
                return $query->where('class_id', $request->class_id);
            })->ignore($id)]
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập.',
            'subject_id.unique' => 'Học phần và lớp đã tồn tại.'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            DB::beginTransaction();
            $this->assignment->update([
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'lecturer_id' => $request->lecturer_id,
                'semester' => $request->semester
            ], $id); 
            DB::commit();
            $jsonFormat['status']  = 1;
            $jsonFormat['message'] = 'Cập nhật thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $jsonFormat['message'] = $e->getMessage();
        }
        return response()->json($jsonFormat);
    }

    public function delete($id) {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        if ($this->assignment->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->assignment->delete($id);
            DB::commit();
            $jsonFormat['status'] = 1;
            $jsonFormat['message'] = 'Xóa thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $jsonFormat['message'] = $e->getMessage();
        }
        return response()->json($jsonFormat);
    }
}
