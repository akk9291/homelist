<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryInterface extends BaseRepositoryInterface
{
    public function getFeatured(int $limit = 5): Collection;

    public function searchProperties(array $filters): Collection;
}
