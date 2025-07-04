<?php

namespace App\Http\Controllers;

use App\Contracts\ProgramRepositoryInterface;
use App\Models\Programme;
use Cache;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(
        protected ProgramRepositoryInterface $programRepository
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->getQueryString();
        $cache_key = "events_$page";

        $programmes = Cache::remember($cache_key, now()->addMinutes(10),function(){
            return Programme::paginate(10)->withQueryString();
        });

        return view("upcoming_events.index", compact("programmes"));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $programme = $this->programRepository->findProgramsBySlug($slug);
            return view('upcoming_events.show-event',compact("programme"));
        } catch (\Exception $e) {
            abort(404,"Event does not exist");
        }
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
    public function destroy(string $id)
    {
        //
    }
}
