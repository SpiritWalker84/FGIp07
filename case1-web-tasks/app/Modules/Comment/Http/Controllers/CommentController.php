<?php

namespace App\Modules\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Services\CommentService;
use App\Modules\Task\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private CommentService $commentService
    ) {}

    public function store(Request $request, Task $task)
    {
        $validated = $request->validate(['body' => 'required|string|max:5000']);
        $this->commentService->create($task, $request->user(), $validated['body']);
        return redirect()->route('tasks.show', $task)->with('success', __('comment.added'));
    }
}
