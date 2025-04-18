@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Reset Password') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">We'll send you a link to reset your password</p>
        </div>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    {{ __('Send Password Reset Link') }}
                </button>
                
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    Back to login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection