<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ScoreRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface ScoreRepository extends RepositoryInterface
{
    public function findAllScore($searchWord, $classId, $subjectId, $start, $limit, $order, $dir);
}
