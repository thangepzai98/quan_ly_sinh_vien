<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LecturerRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface LecturerRepository extends RepositoryInterface
{
    public function findAllLecturer($searchWord, $degree, $facultyId, $start, $limit, $order, $orderBy);
}
