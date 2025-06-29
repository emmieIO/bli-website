<?php

namespace App\Contracts;

interface ProgramRepositoryInterface
{
    public function findProgramsBySlug(string $slug);
}
