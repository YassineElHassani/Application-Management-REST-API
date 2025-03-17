<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobOffer::query();

        if ($request->has('location')) {
            $query->where('location', $request->location);
        }

        if ($request->has('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        $jobOffers = $query->latest('posted_at')->paginate(10);
        
        return response()->json(['job_offers' => $jobOffers]);
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
        if (!$request->user()->isRecruiter()) {
            return response()->json(['message' => 'Unauthorized. Only recruiters can create job offers.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:100',
            'contract_type' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jobOffer = JobOffer::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'contract_type' => $request->contract_type,
            'recruiter_id' => $request->user()->id,
            'posted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Job offer created successfully',
            'job_offer' => $jobOffer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobOffer $jobOffer)
    {
        return response()->json(['job_offer' => $jobOffer]);
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
    public function update(Request $request, JobOffer $jobOffer)
    {
        if ($request->user()->id !== $jobOffer->recruiter_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:100',
            'contract_type' => 'sometimes|required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jobOffer->update($request->only([
            'title', 'description', 'location', 'contract_type'
        ]));

        return response()->json([
            'message' => 'Job offer updated successfully',
            'job_offer' => $jobOffer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, JobOffer $jobOffer)
    {
        if ($request->user()->id !== $jobOffer->recruiter_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $jobOffer->delete();

        return response()->json(['message' => 'Job offer deleted successfully']);
    }
}
