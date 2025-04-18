<?php

use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

// Get unread email count
Route::middleware('auth')->get('/emails/unread-count', function (Request $request) {
    $user = $request->user();
    $inboxFolder = $user->folders()->where('type', 'inbox')->first();
    
    if (!$inboxFolder) {
        return response()->json(['count' => 0]);
    }
    
    $count = Email::where('user_id', $user->id)
                    ->where('folder_id', $inboxFolder->id)
                    ->where('is_read', false)
                    ->count();
                    
    return response()->json(['count' => $count]);
});
