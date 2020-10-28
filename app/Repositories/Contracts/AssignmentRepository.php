<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AssignmentRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface AssignmentRepository extends RepositoryInterface
{
    public function findAllAssignment($searchWord, $subjectId, $classId, $lecturerId, $start, $limit, $order, $orderBy);
}
