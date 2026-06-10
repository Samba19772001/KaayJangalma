<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\TeacherProfile;
use App\Models\ParentProfile;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private function getProfile()
    {
        $user = Auth::user();
        return $user->isParent() ? $user->parentProfile : $user->teacherProfile;
    }

    // ─── Liste des conversations ──────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        if ($user->isParent()) {
            $conversations = Conversation::with(['teacher.user', 'messages' => fn($q) => $q->latest()->limit(1)])
                ->where('parent_id', $user->parentProfile->id)
                ->orderByDesc('last_message_at')
                ->get();
        } else {
            $conversations = Conversation::with(['parent.user', 'messages' => fn($q) => $q->latest()->limit(1)])
                ->where('teacher_id', $user->teacherProfile->id)
                ->orderByDesc('last_message_at')
                ->get();
        }

        return view('messages.index', compact('conversations'));
    }

    // ─── Afficher une conversation ────────────────────────────────
    public function show($conversationId)
    {
        $user         = Auth::user();
        $conversation = Conversation::with(['parent.user', 'teacher.user', 'messages.sender'])
                                    ->findOrFail($conversationId);

        // Vérifier que l'utilisateur appartient à cette conversation
        if ($user->isParent() && $conversation->parent_id !== $user->parentProfile->id) {
            abort(403);
        }
        if ($user->isTeacher() && $conversation->teacher_id !== $user->teacherProfile->id) {
            abort(403);
        }

        // Marquer les messages comme lus
        $conversation->messages()
                     ->where('sender_id', '!=', $user->id)
                     ->where('is_read', false)
                     ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('sender')->oldest()->get();

        return view('messages.show', compact('conversation', 'messages'));
    }

    // ─── Envoyer un message ───────────────────────────────────────
    public function send(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $user         = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Vérifier accès
        if ($user->isParent() && $conversation->parent_id !== $user->parentProfile->id) {
            abort(403);
        }
        if ($user->isTeacher() && $conversation->teacher_id !== $user->teacherProfile->id) {
            abort(403);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'body'            => $request->body,
            'is_read'         => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Créer une notification pour le destinataire
        $recipientId = $user->isParent()
            ? $conversation->teacher->user_id
            : $conversation->parent->user_id;

        Notification::create([
            'user_id' => $recipientId,
            'type'    => 'new_message',
            'data'    => json_encode([
                'message'         => 'Nouveau message de '.$user->name,
                'conversation_id' => $conversation->id,
                'sender'          => $user->name,
            ]),
        ]);

        return back()->with('success', 'Message envoyé.');
    }

    // ─── Nouvelle conversation ────────────────────────────────────
    public function newConversation(Request $request, $teacherId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $user    = Auth::user();
        $parent  = $user->parentProfile;
        $teacher = TeacherProfile::findOrFail($teacherId);

        // Vérifier si conversation existe déjà
        $conversation = Conversation::firstOrCreate(
            ['parent_id' => $parent->id, 'teacher_id' => $teacher->id],
            ['last_message_at' => now()]
        );

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'body'            => $request->body,
            'is_read'         => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Notification au professeur
        Notification::create([
            'user_id' => $teacher->user_id,
            'type'    => 'new_message',
            'data'    => json_encode([
                'message'         => 'Nouveau message de '.$user->name,
                'conversation_id' => $conversation->id,
                'sender'          => $user->name,
            ]),
        ]);

        return redirect()->route('messages.show', $conversation->id)
                         ->with('success', 'Message envoyé !');
    }
}