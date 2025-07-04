<?php

namespace App\Repositories;

use App\Contracts\ProgramRepositoryInterface;
use App\Models\Programme;



class ProgramRepository implements ProgramRepositoryInterface
{
    /**
     * Create a new class instance.
     */

    public function findProgramsBySlug(string $slug): ?Programme{
        return Programme::with("attendees")->where('slug', $slug)->first();
    }
}
