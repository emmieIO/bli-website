<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructors\SavePersonalInfoRequest;
use App\Mail\Instructors\InstructorsApplication;
use App\Models\InstructorProfile;
use App\Models\User;
use App\Rules\VideoUrlRule;
use App\Services\Instructors\InstructorApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class InstructorsController extends Controller
{

    public function __construct(protected InstructorApplicationService $instructorApplicationService)
    {
    }

    public function registerInstructor()
    {
        return view('instructors.become-an-instructor');
    }

    public function resume(string $applicationId){
        $profile = InstructorProfile::where("application_id", $applicationId)
        ->with('user')->firstOrFail();
        $user = $profile->user;
        if(!$profile->is_approved){
            return view('instructors.application-form', compact('user', 'profile'));
        }
        return redirect(route('homepage'))->with([
            "type" => "error",
            "message" => "Your application has already been approved."
        ]);
    }

    public function startApplication(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $email = strtolower(trim($request->email));
        try {
            $user = $this->instructorApplicationService->start($request->email);
            // We silently succeed even if they're already onboarded
            if ($user) {
                Mail::to($user->email)->send(new InstructorsApplication($user));
            }

            return back()->with([
                'type' => 'success',
                'message' => 'Application started successfully. Please check your email for further instructions.'
            ]);
        } catch (\Exception $e) {
            // Log::error('Instructor application error', ['error' => $e->getMessage()]);
            throw $e;

            // return back()->with([
            //     'type' => 'error',
            //     'message' => 'There was a problem starting your application. Please try again.'
            // ]);
        }
    }

    public function showApplicationForm(Request $request)
    {

        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired application link');
        }
        $userId = $request->query('user');
        $user = User::findOrFail($userId);

        if ($user->instructorProfile->status === 'submitted') {
            return view('instructors.application-thank-you');
        }

        $profile = $user->instructorProfile;
        return view('instructors.application-form', compact('user', 'profile'));
    }

    public function savePersonalInformation(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => ['required', 'phone:NG,GB,US', Rule::unique('users', 'phone')->ignore($user->id)],
            'headline' => 'required|string|max:100',
            'bio' => 'required|string|max:1000',
        ],[
            "bio.required" => "Please tell us about yourself.",
            "phone.phone" => "Please enter a valid uk,us or ng phone number."
        ]);

        $saved = $this->instructorApplicationService->savePersonalInfo($validatedData, $user);

        if ($saved) {
            return back()->with([
                "type" => "success",
                "message" => "Personal information saved successfully.",
            ]);
        }

        return back()->with([
            "type" => "error",
            "message" => "An error occurred while saving your personal information.",
        ]);
    }

    public function saveExperienceData(Request $request, User $user)
    {
        $validated = $request->validate([
            "experience" => 'required|string|max:3000',
            'experience_years' => ['required', 'integer', 'between:0,30'],
            'expertise' => "required|string",
            'linkedin' => "nullable|url",
            'website' => 'nullable|url'
        ]);
        $saved = $this->instructorApplicationService->saveExperience($validated, $user);
        if ($saved) {
            return redirect(URL::signedRoute('instructors.application-form', ['user' => $user->id]))->with([
                "type" => "success",
                "message" => "Experience information saved successfully.",
            ]);
        }

        return back()->with([
            "type" => "error",
            "message" => "An error occurred while saving your experience information.",
        ]);
    }

    public function saveInstructorsDocuments(Request $request, User $user)
    {
        $request->validate([
            "resume" => [
                Rule::requiredIf(function () use ($user) {
                    return empty(optional($user->instructorProfile)->resume_path);
                }),
                File::types(['docx', 'pdf'])
                    ->max('2mb')
            ],
            "video_url" => ['required', 'url']
        ]);

        if ($this->instructorApplicationService->saveInstructorDocs($request, $user)) {
            return redirect()->back()->with([
                'type' => "success",
                'message' => "Resume uploaded and video link saved successfully.",
            ]);
        }

        return back()->with([
            'type' => "error",
            'message' => "An error occurred while uploading your resume or saving the video link.",
        ]);

    }

    public function finalizeApplication(Request $request, User $user)
    {
        $request->validate([
            "terms" => ['accepted']
        ], [
            "terms.accepted" => "You must agree to the terms and conditions before submitting your application.",
        ]);
        $missingFields = $this->instructorApplicationService->getIncompleteFields($user);

        if (!empty($missingFields)) {
            return redirect()->back()->with([
                'type' => 'error',
                'message' => 'Please complete all required sections before finalizing your application. Missing: ' . implode(', ', $missingFields),
            ]);
        }

        // You can add logic here to mark the application as finalized, send notifications, etc.
        if ($this->instructorApplicationService->submitApplication($user)) {
            return redirect()->back()->with([
                'type' => 'success',
                'message' => 'Your application has been finalized and submitted successfully.',
            ]);
        }
    }

    public function submitApplication(Request $request, User $user)
    {
        if ($request->has('submit_section')) {
            $section = $request->input('submit_section');

            return match ($section) {
                'personal' => $this->savePersonalInformation($request, $user),
                'experience' => $this->saveExperienceData($request, $user),
                "docs" => $this->saveInstructorsDocuments($request, $user),
                'finalize' => $this->finalizeApplication($request, $user),
                default => back()->with([
                    'type' => 'error',
                    'message' => 'Unknown section submitted.',
                ]),
            };
        }
    }

}
