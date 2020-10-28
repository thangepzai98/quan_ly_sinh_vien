<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Repositories\Contracts\FacultyRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    protected $faculty;

    public function __construct(FacultyRepository $faculty)
    {
        $this->faculty = $faculty;
    }

    public function index()
    {
        return view('faculty.index');
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'faculty_code',
            2 => 'name',
            3 => 'created_at',
            4 => 'options'
        ];
        $searchWord = $request->input('search.value');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $faculties = $this->faculty->findAllFaculty($searchWord, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($faculties['data'])) {
            foreach ($faculties['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getFacultyById/' . $id;
                $urlDelete = '/deleteFaculty/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = $item->name != '' ? $item->name : '';
                $row['index'] = ++$index + $start;
                $row['faculty_code'] = $item->faculty_code != '' ? $item->faculty_code : '---';
                $row['name'] = $item->name != '' ? $item->name : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($faculties['recordsTotal']),
            "recordsFiltered" => intval($faculties['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'faculty_code' => 'required|unique:faculties,faculty_code',
            'name' => 'required',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'faculty_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }
        
        try {
            DB::beginTransaction();
            $this->faculty->create([
                'faculty_code' => $request->faculty_code,
                'name' => $request->name,
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
        if ($this->faculty->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->faculty->find($id);
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
            'faculty_code' => 'required|unique:faculties,faculty_code,' . $id,
            'name' => 'required',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'faculty_code' => 'Mã khoa đã tồn tại',
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            DB::beginTransaction();
            $this->faculty->update([
                'faculty_code' => $request->faculty_code,
                'name' => $request->name,
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
        if ($this->faculty->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->faculty->delete($id);
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

