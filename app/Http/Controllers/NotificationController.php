<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->orderByDesc('created_at')
                                     ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        Notification::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->update(['read_at' => now()]);

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }
}