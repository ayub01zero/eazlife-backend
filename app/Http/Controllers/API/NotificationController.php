<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $notifications = $request->user()->notifications;

        return response()->json($notifications);
    }
}
