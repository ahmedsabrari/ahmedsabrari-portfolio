<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    // GET all contacts (for admin use)
    public function index(Request $request): JsonResponse
    {
        $query = Contact::query();
        
        // Filter by read status
        if ($request->has('read')) {
            $query->whereNotNull('read_at', $request->boolean('read'));
        }
        
        // Sort by latest
        $query->orderBy('created_at', 'desc');
        
        $contacts = $query->get();
        
        return response()->json($contacts);
    }

    // POST create contact
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        // Add IP address
        $data['ip_address'] = $request->ip();
        
        // Add any additional form data
        $data['form_data'] = json_encode($request->except(['name', 'email', 'subject', 'message']));

        $contact = Contact::create($data);

        // Here you can add code to send email notification
        
        return response()->json([
            'message' => 'Contact message sent successfully',
            'data' => $contact
        ], 201);
    }

    // Mark contact as read
    public function markAsRead($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        
        if (!$contact->read_at) {
            $contact->read_at = now();
            $contact->save();
        }
        
        return response()->json(['message' => 'Contact marked as read']);
    }

    // GET single contact
    public function show($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read when viewed
        if (!$contact->read_at) {
            $contact->read_at = now();
            $contact->save();
        }
        
        return response()->json($contact);
    }

    // DELETE contact
    public function destroy($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
    
    // GET contact statistics
    public function stats(): JsonResponse
    {
        $total = Contact::count();
        $unread = Contact::whereNull('read_at')->count();
        $read = $total - $unread;
        
        return response()->json([
            'total' => $total,
            'read' => $read,
            'unread' => $unread
        ]);
    }
}