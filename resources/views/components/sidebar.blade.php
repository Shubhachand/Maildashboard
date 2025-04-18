<div class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <div class="p-4">
        <a href="{{ route('emails.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-center">
            Compose Email
        </a>
    </div>
    
    <nav class="mt-4">
        <div class="px-4 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Folders
        </div>
        
        <ul class="space-y-1">
            @foreach($folders as $folder)
                <li>
                    <a href="{{ route('emails.index', $folder->id) }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium 
                             {{ isset($currentFolder) && $currentFolder->id === $folder->id 
                                ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-700' 
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="mr-3">
                            @if($folder->type === 'inbox')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            @elseif($folder->type === 'sent')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            @elseif($folder->type === 'draft')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            @elseif($folder->type === 'trash')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            @endif
                        </span>
                        <span>{{ $folder->name }}</span>
                        
                        @if($folder->type === 'inbox' && isset($unreadCount) && $unreadCount > 0 && isset($currentFolder) && $currentFolder->id === $folder->id)
                            <span class="ml-auto bg-blue-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>