<?php

namespace App\Services\Course;

use App\Models\Category;
use App\Traits\HasFileUpload;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseCategoryService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(array $data, UploadedFile $file)
    {
        try {
            $file = $this->uploadFile($file, 'categories');
            return DB::transaction(function () use ($data, $file) {
                $category = Category::create([
                    "name" => $data['name'],
                    'description' => $data['description'] ?? null,
                    "image" => $file
                ]);
                return $category;
            });
        } catch (\Throwable $th) {
            Log::error("Error creating category", ['error' => $th->getMessage()]);
            if (!empty($file)) {
                $this->deleteFile($file);
            }
            return null;
        }
    }
    public function update(Category $category, array $data, ?UploadedFile $file = null)
    {
        try {
            //get old image path
            $old_path = $category?->image;
            // define file_path variable to null
            $file_path = null;
            // check if new file is provided, if yes upload it and set the file_path
            if ($file) {
                $file_path = $this->uploadFile($file, 'categories');
            }
            return DB::transaction(function () use ($category, $data, $file_path, $file, $old_path) {
                // prepare update data
                $updateData = [
                    "name" => $data['name'],
                    'description' => $data['description'],
                ];
                // only set image if new file is uploaded
                if ($file_path) {
                    $updateData['image'] = $file_path;
                }
                // update the category
                $category->update($updateData);

                // delete old image if new one is uploaded
                if ($file && !empty($old_path)) {
                    $this->deleteFile($old_path);
                }
                return $category;
            });

        } catch (Exception $th) {
            if ($file_path && $file) {
                $this->deleteFile($file_path);
            }
            Log::error("Error updating category", ['error' => $th->getMessage()]);
            return null;
        }
    }

    public function delete(Category $category)
    {
        $old_path = $category?->image;
        try {
            return DB::transaction(function () use ($category, $old_path) {
                $category->delete();
                if (!empty($old_path)) {
                    DB::afterCommit(function () use ($old_path) {
                        $this->deleteFile($old_path);
                    });
                }
                return true;
            });
        } catch (Exception $th) {
            Log::error('Error deleting category', ['error' => $th->getMessage()]);
            return false;
        }
    }
    public function find(int $id)
    {
    }
    public function getAllCategories(array $filters = [], int $perPage = 10)
    {
        $query = Category::query();

        // Apply filters
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }


        return $query->get();
    }

    public function fetchAll()
    {
        return Category::all();
    }
}
