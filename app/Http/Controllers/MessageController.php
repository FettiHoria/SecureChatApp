<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Trimite un mesaj într-o conversație.
     */
    public function send(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        // verifică dacă userul aparține conversației
        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $request->body
        ]);

        // dacă vrei broadcasting:
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    /**
     * Returnează toate mesajele dintr-o conversație.
     */
    public function index(Request $request, Conversation $conversation)
    {
        // verifică dacă userul are acces
        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}
