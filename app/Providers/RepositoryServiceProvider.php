<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\Contracts\ClassesRepository::class, \App\Repositories\Eloquent\ClassesRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\StudentRepository::class, \App\Repositories\Eloquent\StudentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\LecturerRepository::class, \App\Repositories\Eloquent\LecturerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\PoitRepository::class, \App\Repositories\Eloquent\PoitRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\PointRepository::class, \App\Repositories\Eloquent\PointRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\SubjectRepository::class, \App\Repositories\Eloquent\SubjectRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\FacultyRepository::class, \App\Repositories\Eloquent\FacultyRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AssignmentRepository::class, \App\Repositories\Eloquent\AssignmentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ScoreRepository::class, \App\Repositories\Eloquent\ScoreRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\StudyAgainRepository::class, \App\Repositories\Eloquent\StudyAgainRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\StudyResultRepository::class, \App\Repositories\Eloquent\StudyResultRepositoryEloquent::class);
        //:end-bindings:
    }
}
