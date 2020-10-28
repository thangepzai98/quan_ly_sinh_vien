<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\LecturerRepository;

class LecturerController extends Controller
{

    protected $lecturer;

    public function __construct(LecturerRepository $lecturer)
    {
        $this->lecturer = $lecturer;
    }

    public function index()
    {
        $faculties = Faculty::all();
        return view('lecturer.index', compact('faculties'));
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'lecturer_code',
            2 => 'name',
            3 => 'date_of_birth',
            4 => 'sex',
            5 => 'degree',
            6 => 'faculty_id',
            7 => 'created_at',
            8 => 'options'
        ];
        $searchWord = $request->input('search.value');
        $degree = $request->input('degree');
        $facultyId = $request->input('faculty_id');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $lecturers = $this->lecturer->findAllLecturer($searchWord, $degree, $facultyId, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($lecturers['data'])) {
            foreach ($lecturers['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getLecturerById/' . $id;
                $urlDelete = '/deleteLecturer/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = $item->name != '' ? $item->name : '';
                $row['index'] = ++$index + $start;
                $row['lecturer_code'] = $item->lecturer_code != '' ? $item->lecturer_code : '---';
                $row['name'] = $item->name != '' ? $item->name : '---';
                $row['date_of_birth'] = $item->date_of_birth != '' ? Carbon::parse($item->date_of_birth)->format('d/m/Y') : '---';
                $row['sex'] = $item->sex == 1 ? 'nam' : 'nữ';
                $row['degree'] = $item->degree != '' ? ($item->degree == 1 ? 'Thạc sĩ' : ($item->degree == 2 ? 'Tiến sĩ' : ($item->degree == 3 ? 'Phó giáo sư' : 'Giáo sư'))) : '---';
                $row['faculty_name'] = $item->faculty_id != '' ? Faculty::find($item->faculty_id)->name : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($lecturers['recordsTotal']),
            "recordsFiltered" => intval($lecturers['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'lecturer_code' => 'required|unique:lecturers,lecturer_code',
            'name' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'sex' => 'required|in:1,0',
            'degree' => 'required|in:1,2,3,4',
            'faculty_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'lecturer_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            $dateOfBirth = str_replace('/', '-', $request->date_of_birth);
            $dateOfBirth = Carbon::parse($dateOfBirth);
            DB::beginTransaction();
            $this->lecturer->create([
                'lecturer_code' => $request->lecturer_code,
                'name' => $request->name,
                'date_of_birth' => $dateOfBirth,
                'sex' => $request->sex,
                'degree' => $request->degree,
                'faculty_id' => $request->faculty_id
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

    public function edit($id) {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        if ($this->lecturer->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->lecturer->find($id);
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
            'lecturer_code' => 'required|unique:lecturers,lecturer_code,' . $id,
            'name' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'sex' => 'required|in:1,0',
            'degree' => 'required|in:1,2,3,4',
            'faculty_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'lecturer_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            $dateOfBirth = str_replace('/', '-', $request->date_of_birth);
            $dateOfBirth = Carbon::parse($dateOfBirth);
            DB::beginTransaction();
            $this->lecturer->update([
                'lecturer_code' => $request->lecturer_code,
                'name' => $request->name,
                'date_of_birth' => $dateOfBirth,
                'sex' => $request->sex,
                'degree' => $request->degree,
                'faculty_id' => $request->faculty_id
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
        if ($this->lecturer->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->lecturer->delete($id);
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
