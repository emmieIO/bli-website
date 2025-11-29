<?php

namespace App\Http\Controllers\Mentorship;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use App\Models\MentorshipResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MentorshipResourceController extends Controller
{
    public function index(MentorshipRequest $mentorshipRequest)
    {
        $resources = $mentorshipRequest->resources()
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Mentorship/Resources/Index', [
            'mentorshipRequest' => $mentorshipRequest->load(['student', 'instructor']),
            'resources' => $resources,
        ]);
    }

    public function store(Request $request, MentorshipRequest $mentorshipRequest)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string',
            'category' => 'nullable|string|in:general,assignment,reference,material',
        ]);

        $file = $request->file('file');
        $path = $file->store('mentorship-resources', 'public');

        $mentorshipRequest->resources()->create([
            'uploaded_by' => auth()->id(),
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? 'general',
        ]);

        return back()->with('success', 'Resource uploaded successfully');
    }

    public function download(MentorshipRequest $mentorshipRequest, MentorshipResource $resource)
    {
        if ($resource->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }

    public function destroy(MentorshipRequest $mentorshipRequest, MentorshipResource $resource)
    {
        if ($resource->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();

        return back()->with('success', 'Resource deleted successfully');
    }
}
