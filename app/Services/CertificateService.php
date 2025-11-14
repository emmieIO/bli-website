<?php

namespace App\Services;

use App\Contracts\Services\CertificateServiceInterface;
use App\Contracts\Services\LearningProgressServiceInterface;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CertificateService implements CertificateServiceInterface
{
    public function __construct(
        private LearningProgressServiceInterface $progressService
    ) {}

    /**
     * Check if user has completed all lessons in a course
     */
    public function hasCompletedCourse(User $user, Course $course): bool
    {
        return $this->progressService->isCourseCompleted($user, $course);
    }

    /**
     * Generate certificate for user and course
     */
    public function generateCertificate(User $user, Course $course): Certificate
    {
        // Check if certificate already exists
        $certificate = Certificate::where([
            'user_id' => $user->id,
            'course_id' => $course->id
        ])->first();

        if ($certificate) {
            return $certificate;
        }

        // Create new certificate
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'completion_date' => now(),
            'certificate_data' => [
                'student_name' => $user->name,
                'course_title' => $course->title,
                'instructor_name' => $course->instructor->name ?? 'BLI Academy',
                'completion_date' => now()->format('F j, Y'),
                'course_duration' => $this->calculateCourseDuration($course),
            ]
        ]);

        // Generate PDF certificate
        $this->generateCertificatePDF($certificate);

        return $certificate;
    }

    /**
     * Generate PDF certificate
     */
    private function generateCertificatePDF(Certificate $certificate): void
    {
        $html = view('certificates.template', compact('certificate'))->render();
        
        // For now, we'll store a simple HTML version
        // In production, you'd use a PDF library like DomPDF or wkhtmltopdf
        $filename = "certificate-{$certificate->certificate_number}.html";
        
        Storage::disk('public')->put("certificates/{$filename}", $html);
        
        $certificate->update([
            'certificate_url' => asset("storage/certificates/{$filename}")
        ]);
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(Certificate $certificate): Response
    {
        $html = view('certificates.template', compact('certificate'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="certificate-' . $certificate->certificate_number . '.html"');
    }

    public function getCertificateByNumber(string $certificateNumber): ?Certificate
    {
        return Certificate::where('certificate_number', $certificateNumber)->first();
    }

    public function verifyCertificate(string $certificateNumber): ?Certificate
    {
        return Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'course'])
            ->first();
    }

    /**
     * Calculate total course duration
     */
    private function calculateCourseDuration(Course $course): string
    {
        $totalDuration = 0;
        
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                $totalDuration += $lesson->duration ?? 0;
            }
        }

        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes} minutes";
    }
}