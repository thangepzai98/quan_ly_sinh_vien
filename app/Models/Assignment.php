<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Assignment.
 *
 * @package namespace App\Models;
 */
class Assignment extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'class_id',
        'lecturer_id',
        'semester'
    ];

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Classes');
    }

    public function lecturer()
    {
        return $this->belongsTo('App\Models\Lecturer');
    }
}
