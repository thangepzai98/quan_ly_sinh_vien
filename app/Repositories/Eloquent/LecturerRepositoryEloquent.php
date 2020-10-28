<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\LecturerRepository;
use App\Models\Lecturer;
use App\Validators\LecturerValidator;

/**
 * Class LecturerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class LecturerRepositoryEloquent extends BaseRepository implements LecturerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Lecturer::class;
    }

    public function findAllLecturer($searchWord, $degree, $facultyId, $start, $limit, $order, $orderBy) {
        $query = $this->model;
        if (isset($facultyId) && $facultyId != '') {
            $query = $query->where('faculty_id', $facultyId);
        }
        if (isset($degree) && $degree != '') {
            $query = $query->where('degree', $degree);
        }
        if ($searchWord !== null && $searchWord != '') {
            $query = $query->where('name', 'LIKE', '%' . $searchWord . '%');
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
