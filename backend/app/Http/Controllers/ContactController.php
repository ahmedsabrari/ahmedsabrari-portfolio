<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // GET all contacts (for admin use)
    public function index(Request $request): JsonResponse
    {
        try{
            $this->authorize('viewAny', Contact::class);
            $query = Contact::query();
            
            // Filter by read status
            if ($request->has('read')) {
                if ($request->boolean('read')) {
                    $query->whereNotNull('read_at');
                } else {
                    $query->whereNull('read_at');
                }
            }

            // Filter by date range
            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            // Sort by latest
            $query->orderBy('created_at', 'desc');

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $contacts = $query->paginate($perPage);
            
            return response()->json([
                'data' => ContactResource::collection($contacts),
                'meta' => [
                    'current_page' => $contacts->currentPage(),
                    'total_pages' => $contacts->lastPage(),
                    'total_items' => $contacts->total(),
                    'per_page' => $contacts->perPage(),
                ]
            ]);
        } catch (\Exception $e){
            Log::error('Contact index error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch contacts',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500); 
        }
    }

    // POST create contact
    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Add IP address
            $validated['ip_address'] = $request->ip();
            
            // Add any additional form data
            $validated['form_data'] = json_encode($request->except(['name', 'email', 'subject', 'message']));

            $contact = Contact::create($validated);
            
            Log::info('Contact message created', ['contact_id' => $contact->id]);
            
            // Here you can add code to send email notification
            // $this->sendNotification($contact);
            
            return response()->json([
                'message' => 'Your message has been sent successfully, we will contact you soon',
                'data' => new ContactResource($contact)
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Contact store error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to send message',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Mark contact as read
    public function markAsRead($id): JsonResponse
    {
        try {
            $this->authorize('update', Contact::class);
            
            $contact = Contact::findOrFail($id);
            
            if (!$contact->read_at) {
                $contact->read_at = now();
                $contact->save();
                
                Log::info('Contact marked as read', ['contact_id' => $contact->id]);
            }
            
            return response()->json([
                'message' => 'The message has been marked as read.',
                'data' => new ContactResource($contact)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Contact mark as read error', ['error' => $e->getMessage(), 'contact_id' => $id]);
            return response()->json([
                'message' => 'Failed to mark contact as read',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // GET single contact
    public function show($id): JsonResponse
    {
        try {
            $this->authorize('view', Contact::class);
            
            $contact = Contact::findOrFail($id);
            
            // Mark as read when viewed
            if (!$contact->read_at) {
                $contact->read_at = now();
                $contact->save();
                
                Log::info('Contact marked as read on view', ['contact_id' => $contact->id]);
            }
            
            return response()->json([
                'data' => new ContactResource($contact)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Contact show error', ['error' => $e->getMessage(), 'contact_id' => $id]);
            return response()->json([
                'message' => 'Contact not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not found'
            ], 404);
        }
    }

    // DELETE contact
    public function destroy($id): JsonResponse
    {
        try {
            $this->authorize('delete', Contact::class);
            
            $contact = Contact::findOrFail($id);
            $contact->delete();
            
            Log::info('Contact deleted', ['contact_id' => $id]);
            
            return response()->json([
                'message' => 'تم حذف الرسالة بنجاح'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Contact destroy error', ['error' => $e->getMessage(), 'contact_id' => $id]);
            return response()->json([
                'message' => 'Failed to delete contact',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    
    // GET contact statistics
    public function stats(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Contact::class);
            
            $total = Contact::count();
            $unread = Contact::whereNull('read_at')->count();
            $read = $total - $unread;
            
            // Get stats by date (last 30 days)
            $recentStats = Contact::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            return response()->json([
                'total' => $total,
                'read' => $read,
                'unread' => $unread,
                'recent_stats' => $recentStats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Contact stats error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch contact statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Optional: Method to send notification email
    protected function sendNotification(Contact $contact): void
    {
        // Implement your email notification logic here
        // Example:
        // Mail::to(config('mail.admin_address'))->send(new ContactNotification($contact));
    }
}