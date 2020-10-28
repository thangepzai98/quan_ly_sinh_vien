<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\StudyAgainRepository;
use App\Models\StudyAgain;
use Illuminate\Support\Facades\DB;

/**
 * Class StudyAgainRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class StudyAgainRepositoryEloquent extends BaseRepository implements StudyAgainRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return StudyAgain::class;
    }

    public function findAllStudyAgain($searchWord, $classId, $subjectId, $start, $limit) {
        $query = DB::table('scores')->join('students', 'scores.student_id', '=', 'students.id')
                ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
                ->join('classes', 'students.class_id', '=', 'classes.id')
                ->select('students.student_code', 'students.name as student_name', 'classes.name as class_name', 'subjects.name as subject_name', 'score_1', 'score_2', 'score_3', DB::raw('(score_1*10 + score_2*20 + score_3*70)/100 as total_score'))
                ->where('score_3', '<>', null)
                ->whereRaw('((score_1*10 + score_2*20 + score_3*70)/100) < 4');
        if (isset($classId) && $classId != '') {
            $query = $query->where('students.class_id', $classId);
        }
        if (isset($subjectId) && $subjectId != '') {
            $query = $query->where('scores.subject_id', $subjectId);
        }
        if ($searchWord !== null && $searchWord != '') {
            $query = $query->where('students.name', 'LIKE', '%' . $searchWord . '%')
                            ->orWhere('subjects.name', 'LIKE', '%' . $searchWord . '%')
                            ->orWhere('classes.name', 'LIKE', '%' . $searchWord . '%');
        }
        $count = $query->count();
        $query = $query->orderBy('students.name');
        $model = $query->skip($start)->take($limit)->get();
        return [
            'data' => $model,
            'recordsTotal' => $count
        ];
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
