<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOutcome;
use App\Services\Course\CourseService;
use Illuminate\Http\Request;

class CourseOutcomeController extends Controller
{
    public function __construct(
        public CourseService $courseService
    )
    {}
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
        $validate = $request->validate([
            "outcome"=> "required|string|max:255",
        ]);

        $outcome = $this->courseService->addOutcome($course,$validate['outcome']);
        return back()->with([
            "message" => $outcome ? "Outcome added successfully" : "Error adding outcome",
            "type" => $outcome ? "success" : "error",
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
    public function destroy(Course $course, CourseOutcome $outcome)
    {
        $this->courseService->removeOutcome($outcome, $course);
        return back()->with([
            "message" => "Outcome removed successfully",
            "type" => "success",
        ]);
    }
}
