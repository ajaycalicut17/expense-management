<?php

declare(strict_types=1);

namespace App\Services\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * @return Collection<int, Category>
     */
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
