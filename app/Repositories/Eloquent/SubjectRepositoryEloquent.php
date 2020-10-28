<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\SubjectRepository;
use App\Models\Subject;

/**
 * Class SubjectRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class SubjectRepositoryEloquent extends BaseRepository implements SubjectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Subject::class;
    }

    public function findAllSubject($searchWord, $start, $limit, $order, $orderBy) {
        $query = $this->model;
        if ($searchWord !== null && $searchWord != '') {
            $query = $this->model->where('name', 'LIKE', '%' . $searchWord . '%')
                                ->orWhere('subject_code', 'LIKE', '%' . $searchWord . '%');
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
