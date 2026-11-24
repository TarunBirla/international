<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * Inbox / Conversations list
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Find users with whom there are conversations
        $sentTo = Message::where('sender_id', $userId)->pluck('receiver_id');
        $receivedFrom = Message::where('receiver_id', $userId)->pluck('sender_id');
        $contactIds = $sentTo->merge($receivedFrom)->unique();

        $contacts = User::whereIn('id', $contactIds)->with('company')->get();

        $activeContactId = $request->query('user_id', $contacts->first()->id ?? null);
        $activeContact = $activeContactId ? User::with('company')->find($activeContactId) : null;

        $messages = collect();
        if ($activeContact) {
            $messages = Message::where(function ($q) use ($userId, $activeContactId) {
                $q->where('sender_id', $userId)->where('receiver_id', $activeContactId);
            })->orWhere(function ($q) use ($userId, $activeContactId) {
                $q->where('sender_id', $activeContactId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();

            // Mark received as read
            Message::where('sender_id', $activeContactId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('messages.index', compact('contacts', 'activeContact', 'messages'));
    }

    /**
     * Send Message
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'enquiry_id' => 'nullable|exists:enquiries,id',
            'rfq_id' => 'nullable|exists:rfqs,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'enquiry_id' => $validated['enquiry_id'] ?? null,
            'rfq_id' => $validated['rfq_id'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
