<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\SortBy;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $model;

    protected array $keys;

    public function __construct(Model $model, array $keys)
    {
        $this->model = $model;
        $this->keys = $keys;
    }

    public function getAll(int $sortBy, string $sort_column, array $paginationParams)
    {
        return $this->model->GetAll([], [], $sortBy, $sort_column, $paginationParams,);
    }

    public function create(array $data)
    {

        return $this->model->Insert($data, $this->keys);
    }

    public function update(array $data, int $id)
    {
        return $this->model->UpdateData(["id" => $id], $data);
    }

    public function search(string $data)
    {
        return $this->model->Search($data, [$this->keys],  [], SortBy::UNDEFINED, "", [], "", true);
    }

    public function delete(int $id): bool
    {
        return $this->model->DeleteData(["id" => $id]);
    }

    public function getOne(int $id)
    {
        return $this->model->GetOne(["id" => $id], [], SortBy::UNDEFINED, "");
    }
}
