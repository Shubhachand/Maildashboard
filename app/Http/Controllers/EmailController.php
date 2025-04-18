<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Folder;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    protected $emailService;

    /**
     * Create a new controller instance.
     *
     * @param EmailService $emailService
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        $this->middleware('auth');
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the emails in a specific folder.
     *
     * @param  int  $folderId
     * @return \Illuminate\Http\Response
     */
    public function index($folderId)
    {
        $user = Auth::user();
        $folders = $user->folders()->orderBy('sort_order')->get();
        $currentFolder = Folder::findOrFail($folderId);
        
        // Verify folder belongs to user
        if ($currentFolder->user_id !== $user->id) {
            abort(403);
        }
        
        $emails = Email::where('user_id', $user->id)
                        ->where('folder_id', $folderId)
                        ->orderBy('received_at', 'desc')
                        ->paginate(15);
        
        $unreadCount = Email::where('user_id', $user->id)
                            ->where('folder_id', $folderId)
                            ->where('is_read', false)
                            ->count();
        
        return view('emails.index', [
            'folders' => $folders,
            'currentFolder' => $currentFolder,
            'emails' => $emails,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Show the form for creating a new email.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $folders = $user->folders()->orderBy('sort_order')->get();
        
        return view('emails.create', [
            'folders' => $folders
        ]);
    }

    /**
     * Store a newly created email in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        
        $user = Auth::user();
        $sentFolder = $user->folders()->where('type', 'sent')->first();
        
        if (!$sentFolder) {
            $sentFolder = $user->folders()->create([
                'name' => 'Sent',
                'type' => 'sent',
                'sort_order' => 1
            ]);
        }
        
        // Send email via service
        $result = $this->emailService->sendEmail(
            $user->email,
            $request->to,
            $request->cc ?? '',
            $request->bcc ?? '',
            $request->subject,
            $request->body
        );
        
        if ($result) {
            // Store in sent folder
            Email::create([
                'user_id' => $user->id,
                'folder_id' => $sentFolder->id,
                'from' => $user->email,
                'to' => $request->to,
                'cc' => $request->cc ?? '',
                'bcc' => $request->bcc ?? '',
                'subject' => $request->subject,
                'body' => $request->body,
                'is_read' => true,
                'received_at' => now(),
            ]);
            
            return redirect()->route('emails.index', $sentFolder->id)
                             ->with('success', 'Email sent successfully!');
        }
        
        return back()->withInput()->with('error', 'Failed to send email. Please try again.');
    }

    /**
     * Display the specified email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $email = Email::findOrFail($id);
        
        // Verify email belongs to user
        if ($email->user_id !== $user->id) {
            abort(403);
        }
        
        // Mark as read
        if (!$email->is_read) {
            $email->is_read = true;
            $email->save();
        }
        
        $folders = $user->folders()->orderBy('sort_order')->get();
        
        return view('emails.show', [
            'folders' => $folders,
            'email' => $email,
            'currentFolder' => $email->folder
        ]);
    }

    /**
     * Move email to a different folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
        ]);
        
        $user = Auth::user();
        $email = Email::findOrFail($id);
        $targetFolder = Folder::findOrFail($request->folder_id);
        
        // Verify email and folder belong to user
        if ($email->user_id !== $user->id || $targetFolder->user_id !== $user->id) {
            abort(403);
        }
        
        $email->folder_id = $targetFolder->id;
        $email->save();
        
        return back()->with('success', 'Email moved successfully!');
    }

    /**
     * Toggle the starred status of the email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStar($id)
    {
        $user = Auth::user();
        $email = Email::findOrFail($id);
        
        // Verify email belongs to user
        if ($email->user_id !== $user->id) {
            abort(403);
        }
        
        $email->is_starred = !$email->is_starred;
        $email->save();
        
        return back()->with('success', 'Email updated successfully!');
    }

    /**
     * Toggle the important status of the email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleImportant($id)
    {
        $user = Auth::user();
        $email = Email::findOrFail($id);
        
        // Verify email belongs to user
        if ($email->user_id !== $user->id) {
            abort(403);
        }
        
        $email->is_important = !$email->is_important;
        $email->save();
        
        return back()->with('success', 'Email updated successfully!');
    }

    /**
     * Move email to trash folder.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trash($id)
    {
        $user = Auth::user();
        $email = Email::findOrFail($id);
        
        // Verify email belongs to user
        if ($email->user_id !== $user->id) {
            abort(403);
        }
        
        $trashFolder = $user->folders()->where('type', 'trash')->first();
        
        if (!$trashFolder) {
            $trashFolder = $user->folders()->create([
                'name' => 'Trash',
                'type' => 'trash',
                'sort_order' => 3
            ]);
        }
        
        $email->folder_id = $trashFolder->id;
        $email->save();
        
        return back()->with('success', 'Email moved to trash!');
    }

    /**
     * Remove the specified email from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $email = Email::findOrFail($id);
        
        // Verify email belongs to user
        if ($email->user_id !== $user->id) {
            abort(403);
        }
        
        $email->delete();
        
        return back()->with('success', 'Email permanently deleted!');
    }
}