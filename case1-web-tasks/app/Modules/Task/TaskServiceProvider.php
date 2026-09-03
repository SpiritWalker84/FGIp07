<?php

namespace App\Modules\Task;

use App\Modules\Task\Services\TaskRepository;
use App\Modules\Task\Services\TaskRepositoryInterface;
use App\Modules\Task\Services\TaskService;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->singleton(TaskService::class, function ($app) {
            return new TaskService($app->make(TaskRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
