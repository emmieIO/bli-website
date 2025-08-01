<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Instructors\InstructorService;
use Illuminate\Http\Request;

class InstructorsManagementController extends Controller
{
    public function __construct(protected InstructorService $instructorService){}
    public function index(){
        $instructors = $this->instructorService->fetchPaginatedApproved();
        
        return view('admin.instructors.index', compact("instructors"));
    }
}
