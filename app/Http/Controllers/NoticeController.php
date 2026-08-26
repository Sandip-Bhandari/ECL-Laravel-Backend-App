<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    /**
     * Display a listing of the notices.
     */
    public function index(Request $request)
    {
        try {
            // Latest notices sabse pehle dikhane ke liye orderBy desc use kiya hai
            $notices = Notice::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $notices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created notice in storage.
     */
    public function store(Request $request)
    {
        // Validation check for title and message
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Naya notice database mein create karna
            $notice = Notice::create([
                'title' => $request->title,
                'message' => $request->message,
                'sender_id' => $request->user() ? $request->user()->id : null, // Optional: logged-in admin ID agar auth:sanctum use ho raha hai
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notice broadcasted successfully!',
                'data' => $notice
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to broadcast notice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}