<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Classes;
use App\Models\Faculty;
use App\Models\Score;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\ScoreRepository;

class ScoreController extends Controller
{
    protected $score;

    public function __construct(ScoreRepository $score)
    {
        $this->score = $score;
    }

    public function index()
    {
        $classes = Classes::all();
        $subjects = Subject::all();
        return view('score.index', compact('classes', 'subjects'));
    }

    public function getDataList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'student_code',
            2 => 'student_name',
            3 => 'class_name',
            4 => 'subject_name',
            5 => 'score_1',
            6 => 'score_2',
            7 => 'score_3',
            8 => 'created_at',
            9 => 'options'
        ];
        $searchWord = $request->input('searchWord');
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');
        $scores = $this->score->findAllScore($searchWord, $classId, $subjectId, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($scores['data'])) {
            foreach ($scores['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/getScoreById/' . $id;
                $editMethod = 'GET';
                $row['index'] = ++$index + $start;
                $row['student_code'] = $item->student->student_code != '' ? $item->student->student_code : '---';
                $row['student_name'] = $item->student->name != '' ? $item->student->name : '---';
                $row['class_name'] = $item->student->class->name != '' ? $item->student->class->name : '---';
                $row['subject_name'] = $item->subject->name != '' ? $item->subject->name : '---';
                $row['score_1'] = $item->score_1 != '' ? $item->score_1 : '---';
                $row['score_2'] = $item->score_2 != '' ? $item->score_2 : '---';
                $row['score_3'] = $item->score_3 != '' ? $item->score_3 : '---';
                $row['created_at'] = Carbon::parse($item->created_at)->diffForHumans();
                $row['options'] = Helper::getHtmlEdit($id, $urlEdit, $editMethod);   
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($scores['recordsTotal']),
            "recordsFiltered" => intval($scores['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function edit($id) {
        $jsonFormat = [];
        $jsonFormat['status'] = 0;
        if ($this->score->count(['id' => $id]) > 0) {
            $jsonFormat['status'] = 1;
            $jsonFormat['data']  = $this->score->find($id);
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
           'score_1' => 'nullable|numeric|min:0|max:10',
           'score_2' => 'nullable|numeric|min:0|max:10',
           'score_3' => 'nullable|numeric|min:0|max:10',
        ], [
            'numeric' => 'Điểm không hợp lệ, điểm là kiểu số từ 0 đến 10',
            'min' => 'Điểm không hợp lệ, điểm là kiểu số từ 0 đến 10',
            'max' => 'Điểm không hợp lệ, điểm là kiểu số từ 0 đến 10'
        ]);

        if ($validator->fails()) {
            $jsonFormat['message'] = $validator->errors()->first();
            return response()->json($jsonFormat);
        }

        try {
            DB::beginTransaction();
            if($request->score_1 != '') {
                $this->score->update(['score_1' => $request->score_1], $id);
            }
            if($request->score_2 != '') {
                $this->score->update(['score_2' => $request->score_2], $id);
            }
            if($request->score_3 != '') {
                $this->score->update(['score_3' => $request->score_3], $id);
            }
            DB::commit();
            $jsonFormat['status']  = 1;
            $jsonFormat['message'] = 'Cập nhật thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $jsonFormat['message'] = $e->getMessage();
        }
        return response()->json($jsonFormat);
    }
}
