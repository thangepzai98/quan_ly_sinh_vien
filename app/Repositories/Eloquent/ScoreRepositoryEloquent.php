<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ScoreRepository;
use App\Models\Score;
use App\Validators\ScoreValidator;

/**
 * Class ScoreRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ScoreRepositoryEloquent extends BaseRepository implements ScoreRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Score::class;
    }

    public function findAllScore($searchWord, $classId, $subjectId, $start, $limit, $order, $dir) {
        $query = $this->model;
        if (isset($classId) && $classId != '') {
            $query = $query->whereHas('student', function($query) use($classId) {
                $query->where('class_id', $classId);
            });
        }
        if (isset($subjectId) && $subjectId != '') {
            $query = $query->whereHas('subject', function($query) use($subjectId) {
                $query->where('subject_id', $subjectId);
            });
        }
        if ($searchWord !== null && $searchWord != '') {
            $query = $query->whereHas('student', function($query) use($searchWord) {
                $query->where('name', $searchWord);
            });
        }
        $count = $query->count();
        $query = $query->orderBy($order, $dir);
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
