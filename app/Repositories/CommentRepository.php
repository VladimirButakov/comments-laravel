<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;

class CommentRepository implements CommentRepositoryInterface
{
    public function findById(int $id): ?Comment
    {
        return Comment::with(['user', 'parent', 'children'])->find($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(int $id, array $data, int $userId): ?Comment
    {
        $comment = Comment::where('id', $id)->where('user_id', $userId)->first();
        if (!$comment) {
            return null;
        }
        $comment->update($data);
        return $comment;
    }

    public function delete(int $id, int $userId): bool
    {
        $comment = Comment::where('id', $id)->where('user_id', $userId)->first();
        if (!$comment) {
            return false;
        }
        return $comment->delete();
    }

    public function getByCommentable(
        string $type,
        int $id,
        int $perPage = 15,
        ?string $cursor = null,
        ?int $parentId = null
    ): CursorPaginator {
        $query = Comment::with(['user', 'parent', 'children'])
            ->where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->orderBy('id');

        if ($parentId === null) {
            // Получить только корневые комментарии
            $query->whereNull('parent_id');
        } else {
            // Получить ответы на конкретный комментарий
            $query->where('parent_id', $parentId);
        }

        return $query->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }
}
