<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Classes;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\SubjectRepository;

class SubjectController extends Controller
{
    protected $subject;

    public function __construct(SubjectRepository $subject)
    {
        $this->subject = $subject;
    }

    public function index()
    {
        $classes = Classes::all();
        $lecturers = Lecturer::all();
        return view('subject.index', compact('classes', 'lecturers'));
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'subject_code',
            2 => 'name',
            3 => 'credit',
            4 => 'created_at',
            5 => 'options'
        ];
        $searchWord = $request->input('search.value');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $subjects = $this->subject->findAllSubject($searchWord, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($subjects['data'])) {
            foreach ($subjects['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getSubjectById/' . $id;
                $urlDelete = '/deleteSubject/' . $id; 
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#tableModel";
                $deleteTitle = $item->name != '' ? $item->name : '';
                $row['index'] = ++$index + $start;
                $row['subject_code'] = $item->subject_code != '' ? $item->subject_code : '---';
                $row['name'] = $item->name != '' ? $item->name : '---';
                $row['credit'] = $item->credit != '' ? $item->credit : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle, $tableName);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($subjects['recordsTotal']),
            "recordsFiltered" => intval($subjects['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        $validator = Validator::make($request->all(), [
            'subject_code' => 'required|unique:subjects,subject_code',
            'name' => 'required',
            'credit'  => 'required|numeric|min:1|max:5',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'subject_code' => 'Mã môn học đã tồn tại',
            'credit.numeric' => 'Tín chỉ định dạng không hợp lệ',
            'credit.min' => 'Số tín chỉ nhỏ nhất là 1',
            'credit.max' => 'Số tín chỉ lớn nhất là 5'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            DB::beginTransaction();
            $this->subject->create([
                'subject_code' => $request->subject_code,
                'name' => $request->name,
                'credit' => $request->credit,
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
        if ($this->subject->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->subject->find($id);
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
            'subject_code' => 'required|unique:subjects,subject_code,' . $id,
            'name' => 'required',
            'credit'  => 'required|numeric|min:1|max:5',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'subject_code' => 'Mã môn học đã tồn tại',
            'credit.numeric' => 'Thứ tự phải là kiểu số',
            'credit.min' => 'Số tín chỉ nhỏ nhất là 1',
            'credit.max' => 'Số tín chỉ lớn nhất là 5'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            DB::beginTransaction();
            $this->subject->update([
                'subject_code' => $request->subject_code,
                'name' => $request->name,
                'credit' => $request->credit,
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
        if ($this->subject->count(['id' => $id]) == 0) {
            $jsonFormat['message'] = 'Không tồn tại bản ghi';
            return response()->json($jsonFormat);
        }
        try {
            DB::beginTransaction();
            $this->subject->delete($id);
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
