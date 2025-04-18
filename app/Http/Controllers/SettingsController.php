<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
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
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('settings.index', [
            'user' => $user
        ]);
    }

    /**
     * Update the user's theme preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_preference' => 'required|in:light,dark',
        ]);
        
        $user = Auth::user();
        $user->theme_preference = $request->theme_preference;
        $user->save();
        
        return back()->with('success', 'Theme updated successfully!');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's email settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|string|in:tls,ssl',
        ]);
        
        $user = Auth::user();
        
        // Save settings to the settings table
        $settings = [
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
        ];
        
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => $key],
                ['value' => $value]
            );
        }
        
        return back()->with('success', 'Email settings updated successfully!');
    }
}