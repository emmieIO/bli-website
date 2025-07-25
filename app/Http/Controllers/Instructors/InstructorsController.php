<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Mail\Instructors\InstructorsApplication;
use App\Models\InstructorProfile;
use App\Models\User;
use App\Services\Instructors\InstructorApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstructorsController extends Controller {

    public function __construct( protected InstructorApplicationService $instructorApplicationService ) {
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

    public function applyStepOne() {
        // return view( 'instructors.apply.step-one' );
    }

    public function applyStepTwo() {
        // return view( 'instructors.apply.step-two' );
    }

    public function applyStepThree() {
        // return view( 'instructors.apply.step-three' );
        }
    }
