<?php

namespace App\Infra\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): Model;
    public function create(array $data): Model;
    public function update(int $id, array $data): Model | bool;
    public function delete(int $id): bool;
}
