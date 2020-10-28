<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface StudyAgainRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface StudyAgainRepository extends RepositoryInterface
{
    public function findAllStudyAgain($searchWord, $classId, $subjectId, $start, $limit);
}
