<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Services\TaskService;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    private function filtersFromRequest(Request $request): array
    {
        $user = $request->user();
        $view = $request->get('view', 'involved');

        if ($view === 'all' && !$user->isAdmin()) {
            $view = 'involved';
        }

        $filters = [
            'user_id' => $user->id,
            'scope' => $view,
        ];

        if ($request->filled('status')) {
            $filters['status'] = $request->get('status');
        }

        return $filters;
    }

    public function index(Request $request): View
    {
        $view = $request->get('view', 'involved');
        if ($view === 'all' && !$request->user()->isAdmin()) {
            $view = 'involved';
        }

        $tasks = $this->taskService->paginate(
            (int) $request->get('per_page', 15),
            array_merge(
                $this->filtersFromRequest($request),
                ['scope' => $view]
            )
        );

        return view('tasks.index', compact('tasks', 'view'));
    }

    public function kanban(Request $request): View
    {
        $view = $request->get('view', 'involved');
        if ($view === 'all' && !$request->user()->isAdmin()) {
            $view = 'involved';
        }

        $tasks = $this->taskService->listForUser(array_merge(
            $this->filtersFromRequest($request),
            ['scope' => $view]
        ));

        $columns = [
            Task::STATUS_NEW => $tasks->where('status', Task::STATUS_NEW),
            Task::STATUS_IN_PROGRESS => $tasks->where('status', Task::STATUS_IN_PROGRESS),
            Task::STATUS_DONE => $tasks->where('status', Task::STATUS_DONE),
        ];

        return view('tasks.kanban', compact('columns', 'view'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,done',
        ]);

        $user = $request->user();
        $canEdit = $user->isAdmin()
            || $task->creator_id === $user->id
            || $task->assignee_id === $user->id;

        if (!$canEdit) {
            abort(403);
        }

        $this->taskService->update($task, $validated);

        return redirect()
            ->route('tasks.kanban', ['view' => $request->get('view', 'involved')])
            ->with('success', __('task.updated'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();

        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:new,in_progress,done',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);
        $task = $this->taskService->create($validated, $request->user());

        return redirect()->route('tasks.show', $task)->with('success', __('task.created'));
    }

    public function show(Task $task): View
    {
        $task->load(['creator', 'assignee', 'comments.user']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:new,in_progress,done',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);
        $this->taskService->update($task, $validated);

        return redirect()->route('tasks.show', $task)->with('success', __('task.updated'));
    }

    public function destroy(Request $request, Task $task)
    {
        if (!$request->user()->isAdmin() && $task->creator_id !== $request->user()->id) {
            abort(403);
        }

        $this->taskService->delete($task);

        return redirect()->route('tasks.index')->with('success', __('task.deleted'));
    }
}
