<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllRead(Request $request)
    {
        $user = $request->user();

        // Marque toutes les notifications comme lues
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
