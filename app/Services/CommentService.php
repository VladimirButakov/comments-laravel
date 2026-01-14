<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;

class CommentService
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    public function getById(int $id): ?Comment
    {
        return $this->commentRepository->findById($id);
    }

    public function create(array $data): Comment
    {
        return $this->commentRepository->create($data);
    }

    public function update(int $id, array $data, int $userId): ?Comment
    {
        return $this->commentRepository->update($id, $data, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        return $this->commentRepository->delete($id, $userId);
    }

    public function getByCommentable(
        string $type,
        int $id,
        int $perPage = 15,
        ?string $cursor = null,
        ?int $parentId = null
    ): CursorPaginator {
        return $this->commentRepository->getByCommentable($type, $id, $perPage, $cursor, $parentId);
    }
}
