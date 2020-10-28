<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ClassesRepository;
use App\Models\Classes;
use App\Validators\ClassValidator;

/**
 * Class ClassRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ClassesRepositoryEloquent extends BaseRepository implements ClassesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Classes::class;
    }

    public function findAllClass($searchWord, $facultyId, $start, $limit, $order, $orderBy) {
        $query = $this->model;
        if (isset($facultyId) && $facultyId != '') {
            $query = $query->where('faculty_id', $facultyId);
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
