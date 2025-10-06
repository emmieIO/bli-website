<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Course\CourseCategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        protected CourseCategoryService $courseCategoryService
    ) {
    }
    public function index()
    {
        $categories = $this->courseCategoryService->getAllCategories();
        return view("admin.courses.category.index", compact("categories"));
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
    public function store(Request $request)
    {
        $this->authorize('category-create', Category::class);

        $request->validate([
            "name" => ["required", "string", "unique:categories,name"],
            "category_image" => ["required", "file", "mimes:jpg,jpeg,png,gif,webp,svg", "max:1024"],
            "description" => ["nullable", "string"],
        ]);
        $category_image = $request->file("category_image");
        $category = $this->courseCategoryService->create($request->all(), $category_image);

        if ($category) {
            return redirect()->back()->with([
                "message" => "Category created successfully",
                "type" => "success"
            ]);
        }
        return redirect()->back()->with([
            "message" => "Error creating category",
            "type" => "error"
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
    public function update(Request $request, Category $category)
    {
        $this->authorize('category-update', Category::class);
        $validated = $request->validate([
            "name" => ["required", "string", "unique:categories,name,{$category->id}"],
            "category_image" => ["nullable", "file", "mimes:jpg,jpeg,png,gif,webp,svg", "max:1024"],
            "description" => ["nullable", "string"],
        ]);
        $category_image = $request->file("category_image");
        $category = $this->courseCategoryService->update($category, $validated, $category_image);
        if ($category) {
            return redirect()->back()->with([
                "message" => "Category updated successfully",
                "type" => "success"
            ]);
        }
        return redirect()->back()->with([
            "message" => "Error updating category",
            "type" => "error"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($this->courseCategoryService->delete($category)) {
            return redirect()->back()->with([
                "message" => "Category deleted successfully",
                "type" => "success"
            ]);
        }
        return redirect()->back()->with([
            "message"=> "Error deleting category",
            "type"=> "error"
            ]);
    }
}
