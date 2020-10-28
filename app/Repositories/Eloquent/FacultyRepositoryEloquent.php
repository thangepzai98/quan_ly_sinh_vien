<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\FacultyRepository;
use App\Models\Faculty;
use App\Validators\FacultyValidator;

/**
 * Class FacultyRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class FacultyRepositoryEloquent extends BaseRepository implements FacultyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Faculty::class;
    }

    public function findAllFaculty($searchWord, $start, $limit, $order, $orderBy) {
        $query = $this->model;
        if ($searchWord !== null && $searchWord != '') {
            $query = $this->model->where('name', 'LIKE', '%' . $searchWord . '%')
                                ->orWhere('faculty_code', 'LIKE', '%' . $searchWord . '%');
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
