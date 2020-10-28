<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SubjectRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface SubjectRepository extends RepositoryInterface
{
    public function findAllSubject($searchWord, $start, $limit, $order, $orderBy);
}
