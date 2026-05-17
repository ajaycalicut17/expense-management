<?php

namespace App\Data\Filter;

use Illuminate\Http\Request;

class DateData
{
    public function __construct(
        public ?int $day = null,
        public ?int $month = null,
        public ?int $year = null,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        return new self(
            day: $request->integer('day'),
            month: $request->integer('month'),
            year: $request->integer('year'),
        );
    }
}
