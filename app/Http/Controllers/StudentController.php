<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Repositories\Contracts\StudentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    protected $student;

    public function __construct(StudentRepository $student)
    {
        $this->student = $student;
    }

    public function index()
    {
        $classes = Classes::all();
        return view('student.index', compact('classes'));
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'student_code' => 'required|unique:students,student_code',
            'name' => 'required',
            'date_of_birth' =>'required|date_format:d/m/Y',
            'sex' => 'required|in:0,1',
            'address' => 'required',
            'class_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'student_code.unique' => 'Sinh viên đã tồn tại trong hệ thống',
            'date_of_birth' => 'Ngày sinh không hợp lệ'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            $dateOfBirth = str_replace('/', '-', $request->date_of_birth);
            $dateOfBirth = Carbon::parse($dateOfBirth);
            DB::beginTransaction();
            $this->student->create([
                'student_code' => $request->student_code,
                'name' => $request->name,
                'date_of_birth' => $dateOfBirth,
                'sex' => $request->sex,
                'address' => $request->address,
                'class_id' => $request->class_id
            ]);    
            DB::commit();
            $jsonFormat['status']  = 1;
            $jsonFormat['message'] = 'Thêm thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $jsonFormat['message'] = $e->getMessage();
        }
        return response()->json($jsonFormat);
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'student_code',
            2 => 'name',
            3 => 'date_of_birth',
            4 => 'sex',
            5 => 'address',
            6 => 'class_id',
            7 => 'created_at',
            8 => 'options'
        ];
        $searchWord = $request->input('searchWord');
        $classId = $request->input('class_id');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $students = $this->student->findAllStudent($searchWord, $classId, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($students['data'])) {
            foreach ($students['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getStudentById/' . $id;
                $urlDelete = '/deleteStudent/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = $item->name != '' ? $item->name : '';
                $row['index'] = ++$index + $start;
                $row['student_code'] = $item->student_code != '' ? $item->student_code : '---';
                $row['name'] = $item->name != '' ? $item->name : '---';
                $row['date_of_birth'] = $item->date_of_birth != '' ? Carbon::parse($item->date_of_birth)->format('d/m/Y') : '---';
                $row['sex'] = $item->sex == 1 ? 'nam' : 'nữ';
                $row['address'] = $item->address != '' ? $item->address : '---';
                $row['class_name'] = $item->class_id != '' ? Classes::find($item->class_id)->name : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($students['recordsTotal']),
            "recordsFiltered" => intval($students['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function edit($id) {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        if ($this->student->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->student->find($id);
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
            'student_code' => 'required|unique:students,student_code,' . $id,
            'name' => 'required',
            'date_of_birth' =>'required|date_format:d/m/Y',
            'sex' => 'required|in:0,1',
            'address' => 'required',
            'class_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'student_code.unique' => 'Sinh viên đã tồn tại trong hệ thống',
            'date_of_birth' => 'Ngày sinh không hợp lệ'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            $dateOfBirth = str_replace('/', '-', $request->date_of_birth);
            $dateOfBirth = Carbon::parse($dateOfBirth);
            DB::beginTransaction();
            $this->student->update([
                'student_code' => $request->student_code,
                'name' => $request->name,
                'date_of_birth' => $dateOfBirth,
                'sex' => $request->sex,
                'address' => $request->address,
                'class_id' => $request->class_id
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
        if ($this->student->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->student->delete($id);
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
