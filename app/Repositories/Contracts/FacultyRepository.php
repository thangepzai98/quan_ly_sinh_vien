<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FacultyRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface FacultyRepository extends RepositoryInterface
{
    public function findAllFaculty($searchWord, $start, $limit, $order, $orderBy);
}
