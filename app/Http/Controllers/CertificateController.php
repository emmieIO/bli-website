<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CertificateServiceInterface;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(
        private CertificateServiceInterface $certificateService
    ) {}

    /**
     * Generate and download certificate for a completed course
     */
    public function generate(Course $course)
    {
        $user = auth()->user();
        
        // Check if user completed the course
        if (!$this->certificateService->hasCompletedCourse($user, $course)) {
            return back()->with('error', 'You must complete all lessons to receive a certificate.');
        }

        // Generate or retrieve existing certificate
        $certificate = $this->certificateService->generateCertificate($user, $course);

        return $this->certificateService->downloadCertificate($certificate);
    }

    /**
     * View certificate online
     */
    public function view(Certificate $certificate)
    {
        return view('certificates.view', compact('certificate'));
    }

    /**
     * Verify certificate by certificate number
     */
    public function verify($certificateNumber = null): View
    {
        if (!$certificateNumber) {
            return view('certificates.verify', [
                'certificate' => null,
                'error' => null
            ]);
        }

        $certificate = $this->certificateService->verifyCertificate($certificateNumber);

        if (!$certificate) {
            return view('certificates.verify', [
                'certificate' => null,
                'error' => 'Certificate not found.'
            ]);
        }

        return view('certificates.verify', compact('certificate'));
    }

    /**
     * List user's certificates
     */
    public function index()
    {
        $certificates = auth()->user()->certificates()
            ->with('course')
            ->orderBy('completion_date', 'desc')
            ->get();

        return view('certificates.index', compact('certificates'));
    }
}
