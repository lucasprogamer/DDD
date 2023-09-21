<?php

namespace App\Domain\Schedule\Data;

use Carbon\Carbon;

class SearchScheduleDto
{
    public function __construct(
        public readonly int $user_id,
        public readonly ?string $title = null,
        public readonly ?Carbon $starts_at = null,
        public readonly ?Carbon $ends_at = null
    ) {
    }
}
