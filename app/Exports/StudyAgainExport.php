<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudyAgainExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('scores')->join('students', 'scores.student_id', '=', 'students.id')
                ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
                ->join('classes', 'students.class_id', '=', 'classes.id')
                ->select('students.student_code', 'students.name as student_name', 'classes.name as class_name', 'subjects.name as subject_name', 'score_1', 'score_2', 'score_3', DB::raw('(score_1*10 + score_2*20 + score_3*70)/100 as total_score'))
                ->orderBy('students.name')
                ->where('score_3', '<>', null)
                ->whereRaw('((score_1*10 + score_2*20 + score_3*70)/100) < 4')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Họ tên sinh viên',
            'Lớp',
            'Tên môn học',
            'Điểm chuyên cần',
            'Điểm kiểm tra',
            'Điểm thi',
            'Điểm trung bình'
        ];
    }
}
