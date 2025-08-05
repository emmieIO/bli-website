<?php

namespace Database\Seeders;

use App\Models\InstructorProfile;
use App\Traits\GeneratesApplicationId;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackFillApplicationIdsSeeder extends Seeder
{
    use GeneratesApplicationId;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InstructorProfile::whereNull('application_id')->chunkById(100, function($applications){
            foreach ($applications as $application) {
                $application->update([
                    'application_id'=>$this->formatApplicationId($application->id)
                ]);
            }
        });
    }
}
