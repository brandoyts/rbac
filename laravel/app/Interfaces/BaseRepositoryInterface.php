<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {
    public function findOne(array $criteria):?Model;
    public function findById(int|string $id): ?Model;
    public function create(array $fields): ?Model;
}
