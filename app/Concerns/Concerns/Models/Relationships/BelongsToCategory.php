<?php

declare(strict_types=1);

namespace App\Concerns\Concerns\Models\Relationships;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCategory
{
    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
