<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function findById(int $id): ?Comment;
    public function create(array $data): Comment;
    public function update(int $id, array $data, int $userId): ?Comment;
    public function delete(int $id, int $userId): bool;
    public function getByCommentable(
        string $type,
        int $id,
        int $perPage = 15,
        ?string $cursor = null,
        ?int $parentId = null
    ): CursorPaginator;
}
