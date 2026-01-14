<?php

namespace App\Repositories\Interfaces;

use App\Models\VideoPost;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

interface VideoPostRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?VideoPost;
    public function create(array $data): VideoPost;
    public function update(int $id, array $data): ?VideoPost;
    public function delete(int $id): bool;
    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator;
}




