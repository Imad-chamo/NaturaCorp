<?php

namespace App\Http\Controllers;

use App\Models\NotificationInterne;
use Illuminate\Support\Facades\Auth;

class NotificationInterneController extends Controller
{
    public function fetch()
    {
        return response()->json(
            Auth::user()->notificationsInternes()->latest()->take(10)->get()
        );
    }

    public function markAllAsRead()
    {
        Auth::user()->notificationsInternes()->where('est_lu', false)->update(['est_lu' => true]);
        return response()->json(['success' => true]);
    }

    public function markAsRead(NotificationInterne $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->update(['est_lu' => true]);
        return response()->json(['success' => true]);
    }

    public function destroy(NotificationInterne $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->delete();
        return response()->json(['success' => true]);
    }
}
