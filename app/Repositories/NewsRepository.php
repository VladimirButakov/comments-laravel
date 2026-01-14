<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository implements NewsRepositoryInterface
{
    public function all(): Collection
    {
        return News::all();
    }

    public function findById(int $id): ?News
    {
        return News::find($id);
    }

    public function create(array $data): News
    {
        return News::create($data);
    }

    public function update(int $id, array $data): ?News
    {
        $news = $this->findById($id);
        if (!$news) {
            return null;
        }
        $news->update($data);
        return $news;
    }

    public function delete(int $id): bool
    {
        $news = $this->findById($id);
        if (!$news) {
            return false;
        }
        return $news->delete();
    }

    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator
    {
        return News::orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }
}




