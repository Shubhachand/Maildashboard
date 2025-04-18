@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    @include('components.sidebar')
    
    <div class="flex-1 overflow-auto">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <a href="{{ route('emails.index', $currentFolder->id) }}" class="flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to {{ $currentFolder->name }}
                            </a>
                            
                            <div class="flex space-x-3">
                                <form action="{{ route('emails.star', $email->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                        @if($email->is_starred)
                                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                
                                <form action="{{ route('emails.important', $email->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                        @if($email->is_important)
                                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                
                                <button type="button" onclick="toggleMoveDropdown()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none relative">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                    
                                    <div id="moveDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 ring-1 ring-black ring-opacity-5">
                                        @foreach($folders as $folder)
                                            @if($folder->id !== $currentFolder->id)
                                                <form action="{{ route('emails.move', $email->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Move to {{ $folder->name }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    </div>
                                </button>
                                
                                <form action="{{ route('emails.trash', $email->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                            <h2 class="text-xl font-semibold mb-4">{{ $email->subject }}</h2>
                            
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            From: {{ $email->from }}
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        To: {{ $email->to }}
                                    </div>
                                    @if($email->cc)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            CC: {{ $email->cc }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $email->received_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none mt-6">
                            {!! $email->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleMoveDropdown() {
        document.getElementById('moveDropdown').classList.toggle('hidden');
    }
    
    // Close dropdown when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.closest('button[onclick="toggleMoveDropdown()"]')) {
            document.getElementById('moveDropdown').classList.add('hidden');
        }
    });
</script>
@endsection