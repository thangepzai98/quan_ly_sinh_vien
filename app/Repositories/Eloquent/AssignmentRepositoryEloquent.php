<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AssignmentRepository;
use App\Models\Assignment;
use App\Validators\AssignmentValidator;

/**
 * Class AssignmentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class AssignmentRepositoryEloquent extends BaseRepository implements AssignmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Assignment::class;
    }

    public function findAllAssignment($searchWord, $subjectId, $classId, $lecturerId, $start, $limit, $order, $orderBy) {
        $query = $this->model;
        if (isset($subjectId) && $subjectId != '') {
            $query = $query->where('subject_id', $subjectId);
        }
        if (isset($classId) && $classId != '') {
            $query = $query->where('class_id', $classId);
        }
        if (isset($lecturerId) && $lecturerId != '') {
            $query = $query->where('lecturer_id', $lecturerId);
        }
        if ($searchWord !== null && $searchWord != '') {
            $query = $query->whereHas('subject', function ($query) use ($searchWord) {
                                $query->where('name', 'LIKE', '%' . $searchWord . '%');
                            })->orWhereHas('class', function ($query) use ($searchWord) {
                                $query->where('name', 'LIKE', '%' . $searchWord . '%');
                            })->orWhereHas('lecturer', function ($query) use ($searchWord) {
                                $query->where('name', 'LIKE', '%' . $searchWord . '%');
                            });
        }
        $count = $query->count();
        $query = $query->orderBy($order, $orderBy);
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
