<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ClassesRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Faculty;

class ClassController extends Controller
{

    protected $class;

    public function __construct(ClassesRepository $class)
    {   
        $this->class = $class;
    }

    public function index()
    {
        $faculties = Faculty::all();
        return view('class.index', compact('faculties'));
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'class_code',
            2 => 'name',
            3 => 'faculty_id',
            4 => 'created_at',
            5 => 'options'
        ];
        $searchWord = $request->input('searchWord');
        $facultyId = $request->input('facultyId');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $classes = $this->class->findAllClass($searchWord, $facultyId, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($classes['data'])) {
            foreach ($classes['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getClassById/' . $id;
                $urlDelete = '/deleteClass/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = $item->name != '' ? $item->name : '';
                $row['index'] = ++$index + $start;
                $row['class_code'] = $item->class_code != '' ? $item->class_code : '---';
                $row['name'] = $item->name != '' ? $item->name : '---';
                $row['faculty_name'] = $item->faculty_id != '' ? Faculty::find($item->faculty_id)->name : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($classes['recordsTotal']),
            "recordsFiltered" => intval($classes['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'class_code' => 'required|unique:classes,class_code',
            'name' => 'required',
            'faculty_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'class_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            DB::beginTransaction();
            $this->class->create([
                'class_code' => $request->class_code,
                'name' => $request->name,
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
        if ($this->class->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->class->find($id);
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
            'class_code' => 'required|unique:classes,class_code,' . $id,
            'name' => 'required',
            'faculty_id' => 'required'
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'class_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            DB::beginTransaction();
            $this->class->update([
                'class_code' => $request->class_code,
                'name' => $request->name,
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
        if ($this->class->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->class->delete($id);
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
