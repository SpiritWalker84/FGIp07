<?php

namespace App\Modules\Task\Services;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    public function paginate(int $perPage = 15, ?array $filters = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function listForUser(?array $filters = null): Collection
    {
        return $this->repository->listForUser($filters);
    }

    public function findById(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    public function create(array $data, User $creator): Task
    {
        $data['creator_id'] = $creator->id;
        $data['status'] = $data['status'] ?? Task::STATUS_NEW;
        return $this->repository->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        return $this->repository->update($task, $data);
    }

    public function delete(Task $task): bool
    {
        return $this->repository->delete($task);
    }

    public function assign(Task $task, ?User $assignee): Task
    {
        return $this->update($task, ['assignee_id' => $assignee?->id]);
    }
}
