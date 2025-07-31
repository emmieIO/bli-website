<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorProfile;
use App\Models\User;
use App\Services\Instructors\InstructorApplicationService;
use Illuminate\Http\Request;

class InstructorApplicationController extends Controller
{
    public function __construct(protected InstructorApplicationService $application){}
    public function showApplications(){
        $instructorProfiles = InstructorProfile::with('user')->get();
        return view('admin.instructors.applications', compact('instructorProfiles'));
    }

    public function approve(InstructorProfile $application){
        // Implement the approval logic here, for example:
        if($this->application->approveApplication($application)){
            return redirect()->back()->with([
                "status"=>'success', 
                "message"=>'Application approved successfully.'
            ]);
        }
        return redirect()->back()->with([
            "status" => 'error',
            "message" => 'Failed to approve the application.'
        ]);
    }
    public function deny(){}

    public function view(){}
}
