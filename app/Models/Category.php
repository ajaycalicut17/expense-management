<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\CategoryObserver;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(classes: [CategoryObserver::class])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'name' => AsStringable::class,
        ];
    }
}
