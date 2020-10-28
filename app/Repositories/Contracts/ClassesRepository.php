<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ClassRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface ClassesRepository extends RepositoryInterface
{
    public function findAllClass($searchWord, $facultyId, $start, $limit, $order, $orderBy);
}
