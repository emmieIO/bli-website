<?php

namespace App\Traits;

trait GeneratesApplicationId
{
    /**
     * Generate a unique application ID.
     *
     * @param string $prefix
     * @return string
     */
    public function formatApplicationId(int $intId, $prefix="IN"){
        $formatted = str_pad($intId, 5, 0, STR_PAD_LEFT);
        return "BLI-$prefix-$formatted";
    }
}
