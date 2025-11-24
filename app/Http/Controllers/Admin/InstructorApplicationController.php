<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Instructors\InstructorApplicationService;
use Illuminate\Http\Request;

class InstructorApplicationController extends Controller
{
    public function __construct(protected InstructorApplicationService $application){}


    public function showApplications(){
        $instructorProfiles = InstructorProfile::with('user')
        // ->where('status', 'draft')
        ->get();
        return \Inertia\Inertia::render('Admin/Instructors/Applications', compact('instructorProfiles'));
    }

    public function approve(InstructorProfile $application){
        // Implement the approval logic here, for example:
        if($this->application->approveApplication($application)){
            return redirect()->back()->with([
                "type"=>'success', 
                "message"=>'Application approved successfully.'
            ]);
        }
        return redirect()->back()->with([
            "type" => 'error',
            "message" => 'Failed to approve the application. Ensure the application is complete before approval.'
        ]);
    }
    public function deny(Request $request, InstructorProfile $application){
        $validated = $request->validate([
            "rejection_reason" => ["required", "string", "max:3000"]
        ], [
            "rejection_reason.required" => "Please provide a reason for rejection.",
            "rejection_reason.string" => "The rejection reason must be a valid string.",
            "rejection_reason.max" => "The rejection reason may not be greater than 255 characters.",
        ]);
    
        if($this->application->rejectApplication($validated, $application)){
            return redirect()->back()->with([
                "type" => 'success',
                "message" => 'Application denied successfully.'
            ]);
        }
    }

    public function view(InstructorProfile $application){
        return \Inertia\Inertia::render('Admin/Instructors/ViewApplication', compact('application'));
    }

    public function viewOwnApplication(User $user){
        $application = InstructorProfile::where('user_id', $user->id)
        ->where('status',['submitted','pending', 'approved', 'denied'])
        ->firstOrFail();
        return view('instructors.view-application', compact('application'));
    }
}
