@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Settings</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Profile Settings</h2>
                
                <form action="{{ route('settings.profile') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Theme Settings</h2>
                
                <form action="{{ route('settings.theme') }}" method="POST">
                    @csrf
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="theme_preference" value="light" {{ $user->theme_preference === 'light' ? 'checked' : '' }}
                                   class="form-radio text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Light</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="theme_preference" value="dark" {{ $user->theme_preference === 'dark' ? 'checked' : '' }}
                                   class="form-radio text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Dark</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        Update Theme
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Email Settings</h2>
                
                <form action="{{ route('settings.email') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Host</label>
                        <input type="text" name="mail_host" id="mail_host" value="{{ $user->settings->where('key', 'mail_host')->first()->value ?? config('mail.host') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('mail_host')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                    <label for="mail_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Port</label>
                        <input type="number" name="mail_port" id="mail_port" value="{{ $user->settings->where('key', 'mail_port')->first()->value ?? config('mail.port') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('mail_port')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Username</label>
                        <input type="text" name="mail_username" id="mail_username" value="{{ $user->settings->where('key', 'mail_username')->first()->value ?? config('mail.username') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('mail_username')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Password</label>
                        <input type="password" name="mail_password" id="mail_password" value="{{ $user->settings->where('key', 'mail_password')->first()->value ?? config('mail.password') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('mail_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Encryption</label>
                        <select name="mail_encryption" id="mail_encryption" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="tls" {{ ($user->settings->where('key', 'mail_encryption')->first()->value ?? config('mail.encryption')) === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($user->settings->where('key', 'mail_encryption')->first()->value ?? config('mail.encryption')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                        @error('mail_encryption')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        Update Email Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection