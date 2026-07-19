<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // 1. Get list of active conversations
    public function index(Request $request)
    {
        $userId = $request->user()->user_id;

        // Find all users that have chat history with auth user
        $conversations = Message::query()->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender:user_id,name', 'receiver:user_id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $chats = [];
        $seen = [];

        foreach ($conversations as $msg) {
            $otherUserId = $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
            
            if (!in_array($otherUserId, $seen)) {
                $seen[] = $otherUserId;
                $otherUser = $msg->sender_id == $userId ? $msg->receiver : $msg->sender;
                
                $chats[] = [
                    'user_id' => $otherUserId,
                    'name' => $otherUser ? $otherUser->name : 'Unknown',
                    'last_message' => $msg->message,
                    'created_at' => $msg->created_at,
                    'is_read' => $msg->is_read
                ];
            }
        }

        return response()->json($chats);
    }

    // 2. Get full chat history with a specific user
    public function show(Request $request, $id)
    {
        $authId = $request->user()->user_id;

        $messages = Message::query()->where(function($q) use ($authId, $id) {
                $q->where('sender_id', $authId)->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($authId, $id) {
                $q->where('sender_id', $id)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark incoming messages as read
        Message::query()->where('sender_id', $id)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // 3. Send a new message
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:User,user_id',
            'message' => 'required|string',
            'product_id' => 'nullable|exists:Product,product_id'
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->user_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'product_id' => $request->product_id,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
