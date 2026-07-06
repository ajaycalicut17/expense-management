<?php

declare(strict_types=1);

namespace App\Services\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function all(): Collection
    {
        return Category::query()
            ->select([
                'id',
                'name',
            ])
            ->get();
    }
}
