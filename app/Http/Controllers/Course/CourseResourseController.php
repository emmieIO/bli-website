<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRequirement;
use App\Services\Course\CourseService;
use Illuminate\Http\Request;

class CourseResourseController extends Controller
{
    public function __construct(protected CourseService $courseService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            "requirement" => "required|string|max:255",
        ]);
        $requirement = $this->courseService->addRequirement($course, $validated['requirement']);
        if($requirement){
            return back()->with([
                "message" => "Requirement added successfully",
                "type" => "success",
            ]);
        }
        return back()->with([
            "message" => "Error adding requirement",
            "type" => "error",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, CourseRequirement $requirement)
    {
        $deleted = $this->courseService->removeRequirement($requirement, $course);
        if($deleted){
            return back()->with([
                "message" => "Requirement removed successfully",
                "type" => "success",
            ]);
        }
        return back()->with([
            "message" => "Error removing requirement",
            "type" => "error",
        ]);
        
    }
}
