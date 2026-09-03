<?php

namespace App\Modules\Comment\Services;

use App\Modules\Comment\Models\Comment;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;

class CommentService
{
    public function create(Task $task, User $user, string $body): Comment
    {
        return Comment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'body' => $body,
        ]);
    }

    public function update(Comment $comment, string $body): Comment
    {
        $comment->update(['body' => $body]);
        return $comment->fresh();
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
