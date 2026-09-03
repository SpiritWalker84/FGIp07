<?php

namespace App\Modules\Comment;

use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommentService::class);
    }

    public function boot(): void
    {
        //
    }
}
