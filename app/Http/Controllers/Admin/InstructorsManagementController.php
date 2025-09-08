<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\ApplicationLog;
use App\Models\InstructorProfile;
use App\Services\Instructors\InstructorService;
use App\Traits\HasFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorsManagementController extends Controller
{
    use HasFileUpload;
    public function __construct(protected InstructorService $instructorService){}
    public function index(){
        $instructors = $this->instructorService->fetchPaginatedApproved();
        

        return view('admin.instructors.index', compact("instructors"));
    }

    public function showInstructor(InstructorProfile $instructorProfile){
        $application = $instructorProfile;
        return view("admin.instructors.view-application", compact("application"));
    }
    public function editInstructor(Request $request, InstructorProfile $instructor){
        return view("admin.instructors.edit", compact("instructor"));
    }

    public function updateInstructor(UpdateInstructorRequest $request, InstructorProfile $instructor){
        $uploadedFile = $request->file("resume_path") ?? null;
        $instructor = $this->instructorService->updateInstructor($request->all(), $instructor, $uploadedFile );
        if($instructor){
            return redirect()->back()->with([
                "type" => "success",
                "message" => "Instructor profile updated successfully."
            ]);
        }
        return redirect()->back()->with([
            "type"=> "error",
            "message"=> "Failed to update instructor profile."
            ]);
    }

    public function destroyInstructor(InstructorProfile $instructor){
        if($this->instructorService->deleteInstructor($instructor))
        {
            return redirect()->back()->with([
                "type"=> "success",
                "message"=> "Instructor profile deleted successfully."]);
        }
        return redirect()->back()->with([
            "type"=> "error",
            "message"=> "Failed to delete instructor profile."
            ]);
    }

    public function fetchApplicationLogs(){
        $logs = $this->instructorService->fetchApplicationLogs();

        return view("admin.instructors.application-logs", compact('logs'));
    }

    public function deleteApplicationLog(ApplicationLog $log){
        if($this->instructorService->deleteApplicationLog($log)){
            return redirect()->back()->with([
                'type'=> "success",
                "message" => "Application log deleted successfully."
            ]);
        }
        return redirect()->back()->with([
            'type'=> "error",
            "message" => "Failed to delete application log."
        ]);
    }

    public function restoreInstructor(InstructorProfile $instructor){
        if($this->instructorService->restoreInstructor($instructor)){
            return redirect()->back()->with([
                "type"=> "success",
                "message"=> "Instructor profile restored successfully."
                ]);
        }
        return redirect()->back()->with([
            "type"=> "error",
            "message"=> "Failed to restore instructor profile or already restored."
        ]);
    }


}
