<?php

namespace App\Contracts\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\Certificate;

interface CertificateServiceInterface
{
    public function hasCompletedCourse(User $user, Course $course): bool;
    
    public function generateCertificate(User $user, Course $course): Certificate;
    
    public function getCertificateByNumber(string $certificateNumber): ?Certificate;
    
    public function downloadCertificate(Certificate $certificate): mixed;
    
    public function verifyCertificate(string $certificateNumber): ?Certificate;
}