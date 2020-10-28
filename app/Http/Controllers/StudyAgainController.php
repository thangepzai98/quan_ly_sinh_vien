<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Repositories\Contracts\StudyAgainRepository;
use App\Exports\StudyAgainExport;
use Maatwebsite\Excel\Facades\Excel;

class StudyAgainController extends Controller
{

    protected $studyAgain;

    public function __construct(StudyAgainRepository $studyAgain)
    {
        $this->studyAgain = $studyAgain;
    }

    public function index()
    {
        $classes = Classes::all();
        $subjects = Subject::all();
        return view('studyAgain.index', compact('classes', 'subjects'));
    }

    public function getDataList(Request $request)
    {
        $searchWord = $request->input('searchWord');
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $start = $request->input('start');
        $limit = $request->input('length');
        $studyAgains = $this->studyAgain->findAllStudyAgain($searchWord, $classId, $subjectId, $start, $limit);
        $data = [];
        if(!empty($studyAgains['data'])) {
            foreach ($studyAgains['data'] as $index => $item) {
                $row['index'] = ++$index + $start;
                $row['student_code'] = $item->student_code != '' ? $item->student_code : '---';
                $row['student_name'] = $item->student_name != '' ? $item->student_name : '---';
                $row['class_name'] = $item->class_name != '' ? $item->class_name : '---';
                $row['subject_name'] = $item->subject_name != '' ? $item->subject_name : '---';
                $row['score_1'] = $item->score_1 != '' ? $item->score_1 : '---';
                $row['score_2'] = $item->score_2 != '' ? $item->score_2 : '---';
                $row['score_3'] = $item->score_3 != '' ? $item->score_3 : '---';
                $row['total_score'] = $item->total_score != '' ? round($item->total_score, 2) : '---';
                $data[] = $row;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($studyAgains['recordsTotal']),
            "recordsFiltered" => intval($studyAgains['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    public function export() 
    {
        return Excel::download(new StudyAgainExport, 'sinhvienhoclai.xlsx');
    }
}
