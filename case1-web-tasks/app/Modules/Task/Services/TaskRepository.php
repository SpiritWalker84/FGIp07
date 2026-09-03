<?php

namespace App\Modules\Task\Services;

use App\Modules\Task\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function find(int $id): ?Task
    {
        return Task::with(['creator', 'assignee', 'comments'])->find($id);
    }

    public function paginate(int $perPage = 15, ?array $filters = null): LengthAwarePaginator
    {
        $query = Task::with(['creator', 'assignee']);

        if ($filters) {
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['assignee_id'])) {
                $query->where('assignee_id', $filters['assignee_id']);
            }
            if (!empty($filters['creator_id'])) {
                $query->where('creator_id', $filters['creator_id']);
            }
            if (!empty($filters['scope']) && !empty($filters['user_id'])) {
                $scope = $filters['scope'];
                $uid = $filters['user_id'];
                if ($scope === 'assigned') {
                    $query->where('assignee_id', $uid);
                } elseif ($scope === 'created') {
                    $query->where('creator_id', $uid);
                } elseif ($scope === 'involved') {
                    $query->where(function ($q) use ($uid) {
                        $q->where('assignee_id', $uid)->orWhere('creator_id', $uid);
                    });
                }
            }
        }

        return $query->latest()->paginate($perPage);
    }

    public function listForUser(?array $filters = null): Collection
    {
        $query = Task::with(['creator', 'assignee']);

        if ($filters) {
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['assignee_id'])) {
                $query->where('assignee_id', $filters['assignee_id']);
            }
            if (!empty($filters['creator_id'])) {
                $query->where('creator_id', $filters['creator_id']);
            }
            if (!empty($filters['scope']) && $filters['scope'] === 'assigned') {
                $query->where('assignee_id', $filters['user_id']);
            }
            if (!empty($filters['scope']) && $filters['scope'] === 'created') {
                $query->where('creator_id', $filters['user_id']);
            }
            if (!empty($filters['scope']) && $filters['scope'] === 'involved') {
                $uid = $filters['user_id'];
                $query->where(function ($q) use ($uid) {
                    $q->where('assignee_id', $uid)->orWhere('creator_id', $uid);
                });
            }
        }

        return $query->latest()->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
