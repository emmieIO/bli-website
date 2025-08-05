<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationLog;
use App\Services\Instructors\InstructorService;
use Illuminate\Http\Request;

class InstructorsManagementController extends Controller
{
    public function __construct(protected InstructorService $instructorService){}
    public function index(){
        $instructors = $this->instructorService->fetchPaginatedApproved();

        return view('admin.instructors.index', compact("instructors"));
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


}
