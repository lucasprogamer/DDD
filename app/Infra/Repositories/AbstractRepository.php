<?php

namespace App\Infra\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements AbstractRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model | false
    {
        $record = $this->find($id);

        if ($record->update($data)) {
            return $record;
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);

        if ($record) {
            $record->delete();
            return true;
        }

        return false;
    }
}
