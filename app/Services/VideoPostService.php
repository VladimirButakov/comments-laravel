<?php

namespace App\Services;

use App\Models\VideoPost;
use App\Repositories\Interfaces\VideoPostRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class VideoPostService
{
    public function __construct(
        private VideoPostRepositoryInterface $videoPostRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->videoPostRepository->all();
    }

    public function getById(int $id): ?VideoPost
    {
        return $this->videoPostRepository->findById($id);
    }

    public function create(array $data): VideoPost
    {
        return $this->videoPostRepository->create($data);
    }

    public function update(int $id, array $data): ?VideoPost
    {
        return $this->videoPostRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->videoPostRepository->delete($id);
    }

    public function getPaginated(int $perPage = 15, ?string $cursor = null): CursorPaginator
    {
        return $this->videoPostRepository->getPaginated($perPage, $cursor);
    }
}




