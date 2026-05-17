<?php

namespace App\Data\Filter;

class DateData
{
    public function __construct(
        public ?int $day = null,
        public ?int $month = null,
        public ?int $year = null,
    ) {}
}
