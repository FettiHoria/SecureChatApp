<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Creează o conversație privată între userul logat și alt user.
     */
    public function create(Request $request)
    {
        $request->validate([
            'other_user_id' => 'required|exists:users,id'
        ]);

        // verifică dacă nu există deja conversația
        $existing = Conversation::where('type', 'private')
            ->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', auth()->id());
            })
            ->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->other_user_id);
            })
            ->first();

        if ($existing) {
            return response()->json($existing, 200);
        }

        // creează conversația
        $conversation = Conversation::create([
            'type' => 'private'
        ]);

        // atașează participanții
        $conversation->users()->attach([
            auth()->id(),
            $request->other_user_id
        ]);

        return response()->json($conversation, 201);
    }

    /**
     * Listează conversațiile în care userul este participant.
     */
    public function index(Request $request)
    {
        $conversations = Conversation::whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('users')->get();

        return response()->json($conversations);
    }
}
