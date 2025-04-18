@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    @include('components.sidebar')
    
    <div class="flex-1 overflow-auto">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold">
                                {{ $currentFolder->name }} ({{ $emails->total() }})
                            </h2>
                            
                            @if($currentFolder->type === 'inbox' && $unreadCount > 0)
                                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm font-medium px-3 py-1 rounded-full">
                                    {{ $unreadCount }} unread
                                </span>
                            @endif
                        </div>
                        
                        @if($emails->isEmpty())
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium">No emails</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no emails in this folder.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($emails as $email)
                                            <tr class="{{ $email->is_read ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <form action="{{ route('emails.star', $email->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="focus:outline-none">
                                                                @if($email->is_starred)
                                                                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                                    </svg>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('emails.show', $email->id) }}" class="block">
                                                        <div class="text-sm font-medium {{ $email->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-gray-900 dark:text-white font-semibold' }}">
                                                            {{ $email->from }}
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('emails.show', $email->id) }}" class="block">
                                                        <div class="text-sm {{ $email->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-gray-900 dark:text-white font-semibold' }}">
                                                            {{ $email->subject }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-500 truncate max-w-md">
                                                            {{ strip_tags($email->body) }}
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                                    {{ $email->received_at->format('M d, H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <form action="{{ route('emails.trash', $email->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-gray-400 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $emails->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection