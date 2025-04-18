<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
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
     * Display a listing of the folders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $folders = $user->folders()->orderBy('sort_order')->get();
        
        return view('folders.index', [
            'folders' => $folders
        ]);
    }

    /**
     * Show the form for creating a new folder.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('folders.create');
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        $maxSortOrder = $user->folders()->max('sort_order') ?? 0;
        
        $folder = $user->folders()->create([
            'name' => $request->name,
            'type' => 'custom',
            'sort_order' => $maxSortOrder + 1
        ]);
        
        return redirect()->route('folders.index')
                         ->with('success', 'Folder created successfully!');
    }

    /**
     * Show the form for editing the specified folder.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $folder = Folder::findOrFail($id);
        
        // Verify folder belongs to user
        if ($folder->user_id !== $user->id) {
            abort(403);
        }
        
        // System folders can't be edited
        if (in_array($folder->type, ['inbox', 'sent', 'draft', 'trash'])) {
            return redirect()->route('folders.index')
                             ->with('error', 'System folders cannot be edited.');
        }
        
        return view('folders.edit', [
            'folder' => $folder
        ]);
    }

    /**
     * Update the specified folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        $folder = Folder::findOrFail($id);
        
        // Verify folder belongs to user
        if ($folder->user_id !== $user->id) {
            abort(403);
        }
        
        // System folders can't be edited
        if (in_array($folder->type, ['inbox', 'sent', 'draft', 'trash'])) {
            return redirect()->route('folders.index')
                             ->with('error', 'System folders cannot be edited.');
        }
        
        $folder->name = $request->name;
        $folder->save();
        
        return redirect()->route('folders.index')
                         ->with('success', 'Folder updated successfully!');
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $folder = Folder::findOrFail($id);
        
        // Verify folder belongs to user
        if ($folder->user_id !== $user->id) {
            abort(403);
        }
        
        // System folders can't be deleted
        if (in_array($folder->type, ['inbox', 'sent', 'draft', 'trash'])) {
            return redirect()->route('folders.index')
                             ->with('error', 'System folders cannot be deleted.');
        }
        
        // Move emails to inbox
        $inboxFolder = $user->folders()->where('type', 'inbox')->first();
        
        if ($inboxFolder) {
            $folder->emails()->update(['folder_id' => $inboxFolder->id]);
        }
        
        $folder->delete();
        
        return redirect()->route('folders.index')
                         ->with('success', 'Folder deleted successfully!');
    }

    /**
     * Reorder folders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'folders' => 'required|array',
            'folders.*' => 'exists:folders,id',
        ]);
        
        $user = Auth::user();
        
        foreach ($request->folders as $index => $folderId) {
            $folder = Folder::findOrFail($folderId);
            
            // Verify folder belongs to user
            if ($folder->user_id !== $user->id) {
                continue;
            }
            
            $folder->sort_order = $index;
            $folder->save();
        }
        
        return response()->json(['success' => true]);
    }
}