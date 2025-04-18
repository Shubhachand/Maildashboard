<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $folders = $user->folders()->orderBy('sort_order')->get();
        $inboxFolder = $folders->where('type', 'inbox')->first();
        
        if (!$inboxFolder) {
            $inboxFolder = $user->folders()->create([
                'name' => 'Inbox',
                'type' => 'inbox',
                'sort_order' => 0
            ]);
        }
        
        $emails = Email::where('user_id', $user->id)
                        ->where('folder_id', $inboxFolder->id)
                        ->orderBy('received_at', 'desc')
                        ->paginate(15);
        
        $unreadCount = Email::where('user_id', $user->id)
                            ->where('folder_id', $inboxFolder->id)
                            ->where('is_read', false)
                            ->count();
        
        return view('dashboard.index', [
            'folders' => $folders,
            'currentFolder' => $inboxFolder,
            'emails' => $emails,
            'unreadCount' => $unreadCount
        ]);
    }
}