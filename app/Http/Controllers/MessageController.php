<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $messages = Auth::user()->receivedMessages()
                    ->with(['sender', 'annonce'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('messages.index', compact('messages'));
    }
    public function sent()
    {
        $messages = Auth::user()->sentMessages()
                    ->with(['receiver', 'annonce'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('messages.sent', compact('messages'));
    }
    public function create(Request $request)
    {
        $annonce = null;
        $receiver = null;
        
        if ($request->has('annonce')) {
            $annonce = Annonce::findOrFail($request->annonce);
            $receiver = $annonce->user;
        } elseif ($request->has('receiver')) {
            $receiver = User::findOrFail($request->receiver);
        }
        
        return view('messages.create', compact('annonce', 'receiver'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'annonce_id' => 'nullable|exists:annonces,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        if ($validated['receiver_id'] == Auth::id()) {
            return back()->withErrors(['receiver_id' => 'Vous ne pouvez pas vous envoyer un message à vous-même.']);
        }
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'annonce_id' => $validated['annonce_id'] ?? null,
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'is_read' => false,
        ]);
        
        return redirect()->route('messages.sent')
            ->with('success', 'Message envoyé avec succès.');
    }
    public function show(Message $message)
    {
        if ($message->receiver_id != Auth::id() && $message->sender_id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        if ($message->receiver_id == Auth::id() && !$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        return view('messages.show', compact('message'));
    }
    public function reply(Message $message)
    {
        if ($message->receiver_id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $receiver = User::find($message->sender_id);
        $annonce = $message->annonce;
        $subject = 'Re: ' . $message->subject;
        
        return view('messages.reply', compact('message', 'receiver', 'annonce', 'subject'));
    }
    public function destroy(Message $message)
    {
        if ($message->receiver_id != Auth::id() && $message->sender_id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $message->delete();
        
        return back()->with('success', 'Message supprimé avec succès.');
    }
}