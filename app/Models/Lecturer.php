<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Lecturer.
 *
 * @package namespace App\Models;
 */
class Lecturer extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lecturer_code',
        'name',
        'date_of_birth',
        'sex',
        'degree',
        'faculty_id'
    ];

}
