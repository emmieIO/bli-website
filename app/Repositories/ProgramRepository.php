<?php

namespace App\Repositories;

use App\Contracts\ProgramRepositoryInterface;
use App\Models\Event;


class ProgramRepository implements ProgramRepositoryInterface
{
    /**
     * Create a new class instance.
     */

    public function findProgramsBySlug(string $slug): ?Event{
        return Event::with("attendees")->where('slug', $slug)->first();
    }
}
