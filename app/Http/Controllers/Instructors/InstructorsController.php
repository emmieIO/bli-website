<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructors\SavePersonalInfoRequest;
use App\Mail\Instructors\InstructorsApplication;
use App\Models\InstructorProfile;
use App\Models\User;
use App\Services\Instructors\InstructorApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstructorsController extends Controller {

    public function __construct(protected InstructorApplicationService $instructorApplicationService ) {
    }

    public function registerInstructor() {
        return view( 'instructors.become-an-instructor' );
    }

    public function startApplication( Request $request ) {
        $request->validate( [
            'email' => [ 'required', 'email' ]
        ] );
        $email = strtolower( trim( $request->email ) );
        try {
            $user = $this->instructorApplicationService->start( $request->email );

            // We silently succeed even if they're already onboarded
        if ($user) {
            Mail::to($user->email)->send(new InstructorsApplication($user));
        }

        return back()->with([
            'type' => 'success',
            'message' => 'Application started successfully. Please check your email for further instructions.'
        ]);
        } catch ( \Exception $e ) {
        Log::error('Instructor application error', ['error' => $e->getMessage()]);

        return back()->with([
            'type' => 'error',
            'message' => 'There was a problem starting your application. Please try again.'
        ]);
        }
    }

    public function showApplicationForm(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired application link');
        }
        $userId = $request->query('user');
        $user = User::findOrFail($userId);
        if($user){

            $profile = $user->instructorProfile;
        }


        return view( 'instructors.application-form' ,compact('user', 'profile'));
    }

public function savePersonalInformation(Request $request, User $user)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:50',
        'headline' => 'required|string|max:100',
        'bio' => 'required|string|max:1000',
    ]);

    $saved = $this->instructorApplicationService->savePersonalInfo($validatedData, $user);

    if ($saved) {
        logger()->info($saved);
        return redirect(URL::signedRoute('instructors.application-form', ['user' => $user->id]))->with([
            "type" => "success",
            "message" => "Personal information saved successfully.",
        ]);
    }

    return back()->with([
        "type" => "error",
        "message" => "An error occurred while saving your personal information.",
    ]);
}

public function saveExperienceData(Request $request, User $user){
        $validated = $request->validate([
            "experience" => 'required|string|max:1000',
            'experience_years' => ['required', 'integer', 'between:0,30'],
            'expertise' => "required|string",
            // 'expertise.*' => ['string', 'in:' . implode(',', config('expertise'))],
            'linkedin' => "nullable|url|max:250",
            'website' => 'nullable|url|max:255'
        ]);
        $saved = $this->instructorApplicationService->saveExperience($validated, $user);
}
    public function submitApplication(Request $request, User $user) {
        if($request->has('submit_section')){
            $section = $request->input('submit_section');

            switch($section){
                case 'personal':
                return $this->savePersonalInformation($request, $user);

                case "experience":
                    return $this->saveExperienceData($request, $user);


            }
        }
    }

    }
