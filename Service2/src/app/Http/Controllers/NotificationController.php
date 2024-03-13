<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;


class NotificationController extends Controller
{
    

    public function getAllNotification()
    {
        $users = Notification::all();
        return response()->json([
            'notification' => $users,
        ], 200);
    }
}
