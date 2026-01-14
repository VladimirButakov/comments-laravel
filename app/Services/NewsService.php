<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsService
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->newsRepository->all();
    }

    public function getById(int $id): ?News
    {
        return $this->newsRepository->findById($id);
    }

    public function create(array $data): News
    {
        return $this->newsRepository->create($data);
    }

    public function update(int $id, array $data): ?News
    {
        return $this->newsRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->newsRepository->delete($id);
    }

    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator
    {
        return $this->newsRepository->getPaginated($perPage, $cursor);
    }
}




