<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\StudyResultRepository;
use App\Models\StudyResult;
use App\Validators\StudyResultValidator;

/**
 * Class StudyResultRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class StudyResultRepositoryEloquent extends BaseRepository implements StudyResultRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return StudyResult::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
