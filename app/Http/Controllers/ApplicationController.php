<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CV;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If user is a candidate, show their applications
        if ($request->user()->isCandidate()) {
            $applications = $request->user()->applications()->with(['jobOffer', 'cv'])->get();
        } 
        // If user is a recruiter, show applications for their job offers
        else if ($request->user()->isRecruiter()) {
            $applications = Application::whereHas('jobOffer', function ($query) use ($request) {
                $query->where('recruiter_id', $request->user()->id);
            })->with(['user', 'cv', 'jobOffer'])->get();
        }

        return response()->json(['applications' => $applications]);
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
        if (!$request->user()->isCandidate()) {
            return response()->json(['message' => 'Unauthorized. Only candidates can apply for jobs.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'job_offer_id' => 'required|exists:job_offers,id',
            'cv_id' => 'required|exists:cvs,id',
            'cover_letter' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cv = CV::findOrFail($request->cv_id);
        if ($cv->user_id !== $request->user()->id) {
            return response()->json(['message' => 'The CV does not belong to you'], 403);
        }

        $existingApplication = Application::where('user_id', $request->user()->id)
            ->where('job_offer_id', $request->job_offer_id)
            ->first();

        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied for this job'], 422);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_offer_id' => $request->job_offer_id,
            'cv_id' => $request->cv_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $application->load(['jobOffer', 'cv', 'user']);
        return response()->json(['application' => $application]);
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
    public function update(Request $request, Application $application)
    {
        if (!$request->user()->isRecruiter()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($application->jobOffer->recruiter_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Application status updated successfully',
            'application' => $application,
        ]);
    }

    public function applyMultiple(Request $request)
    {
        if (!$request->user()->isCandidate()) {
            return response()->json(['message' => 'Unauthorized. Only candidates can apply for jobs.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'job_offer_ids' => 'required|array',
            'job_offer_ids.*' => 'exists:job_offers,id',
            'cv_id' => 'required|exists:cvs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cv = CV::findOrFail($request->cv_id);
        if ($cv->user_id !== $request->user()->id) {
            return response()->json(['message' => 'The CV does not belong to you'], 403);
        }

        $results = [];
        
        foreach ($request->job_offer_ids as $jobOfferId) {
            $existingApplication = Application::where('user_id', $request->user()->id)
                ->where('job_offer_id', $jobOfferId)
                ->first();

            if ($existingApplication) {
                $results[] = [
                    'job_offer_id' => $jobOfferId,
                    'status' => 'error',
                    'message' => 'Already applied',
                ];
                continue;
            }

            $application = Application::create([
                'user_id' => $request->user()->id,
                'job_offer_id' => $jobOfferId,
                'cv_id' => $request->cv_id,
                'status' => 'pending',
            ]);

            $results[] = [
                'job_offer_id' => $jobOfferId,
                'status' => 'success',
                'application_id' => $application->id,
            ];
        }

        return response()->json([
            'message' => 'Multiple applications processed',
            'results' => $results,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
