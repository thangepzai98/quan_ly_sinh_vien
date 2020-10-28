<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\PointRepository;
use App\Models\Point;
use App\Validators\PointValidator;

/**
 * Class PointRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class PointRepositoryEloquent extends BaseRepository implements PointRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Point::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
