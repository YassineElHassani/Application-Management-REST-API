<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CVController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cvs = $request->user()->cvs;
        return response()->json(['cvs' => $cvs]);
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
        $validator = Validator::make($request->all(), [
            'cv' => 'required|file|mimes:pdf,docx|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('cv');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        $path = $file->storeAs('cvs', $fileName, 'public');
        
        $cv = CV::create([
            'user_id' => $request->user()->id,
            'file_path' => $path,
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'CV uploaded successfully',
            'cv' => $cv,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CV $cv)
    {
        return response()->json(['cv' => $cv]);
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
    public function destroy(CV $cv)
    {
        Storage::disk('public')->delete($cv->file_path);
        
        $cv->delete();

        return response()->json(['message' => 'CV deleted successfully']);
    }
}
