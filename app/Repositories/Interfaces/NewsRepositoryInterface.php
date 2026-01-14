<?php

namespace App\Repositories\Interfaces;

use App\Models\News;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?News;
    public function create(array $data): News;
    public function update(int $id, array $data): ?News;
    public function delete(int $id): bool;
    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator;
}




