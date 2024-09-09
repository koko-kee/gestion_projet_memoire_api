<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
 /**
     * Récupérer toutes les notifications non lues de l'utilisateur connecté.
     */
    public function getUnreadNotifications()
    {
        $notifications = Auth::user()->unreadNotifications;

        return response()->json($notifications);
    }

    /**
     * Récupérer toutes les notifications (lues et non lues) de l'utilisateur connecté.
     */
    public function getAllNotifications()
    {
        $notifications = Auth::user()->notifications;

        return response()->json($notifications);
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marquée comme lue']);
        }

        return response()->json(['message' => 'Notification non trouvée'], 404);
    }

    /**
     * Marquer toutes les notifications comme lues.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Toutes les notifications ont été marquées comme lues']);
    }

    /**
     * Supprimer une notification.
     */
    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification supprimée']);
        }

        return response()->json(['message' => 'Notification non trouvée'], 404);
    }
}
