<?php

namespace App\Modules\Task\Services;

use App\Modules\Task\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function find(int $id): ?Task;

    public function paginate(int $perPage = 15, ?array $filters = null): LengthAwarePaginator;

    public function listForUser(?array $filters = null): Collection;

    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;
}
