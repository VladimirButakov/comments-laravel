<?php

namespace App\Repositories;

use App\Models\VideoPost;
use App\Repositories\Interfaces\VideoPostRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class VideoPostRepository implements VideoPostRepositoryInterface
{
    public function all(): Collection
    {
        return VideoPost::all();
    }

    public function findById(int $id): ?VideoPost
    {
        return VideoPost::find($id);
    }

    public function create(array $data): VideoPost
    {
        return VideoPost::create($data);
    }

    public function update(int $id, array $data): ?VideoPost
    {
        $post = $this->findById($id);
        if (!$post) {
            return null;
        }
        $post->update($data);
        return $post;
    }

    public function delete(int $id): bool
    {
        $post = $this->findById($id);
        if (!$post) {
            return false;
        }
        return $post->delete();
    }

    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator
    {
        return VideoPost::orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }
}




