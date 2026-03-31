<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Exam Results: <span class="text-indigo-600">{{ $liveExam->title }}</span>
            </h2>
            <a href="{{ route('admin.live-exams.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                <i class="fa fa-arrow-left mr-2"></i>Back to Exams
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4 flex justify-between items-center border border-gray-100">
                <div>
                    <span class="text-gray-600 uppercase text-xs font-bold tracking-wider">Total Participants:</span> 
                    <span class="text-2xl font-bold text-indigo-600">{{ $attempts->total() }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Submitted At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attempts as $index => $attempt)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($index == 0 && $attempts->currentPage() == 1)
                                            <span class="text-yellow-500 font-bold"><i class="fa fa-trophy mr-1"></i> 1st</span>
                                        @elseif($index == 1 && $attempts->currentPage() == 1)
                                            <span class="text-gray-400 font-bold"><i class="fa fa-medal mr-1"></i> 2nd</span>
                                        @elseif($index == 2 && $attempts->currentPage() == 1)
                                            <span class="text-orange-400 font-bold"><i class="fa fa-medal mr-1"></i> 3rd</span>
                                        @else
                                            <span class="text-gray-900 font-medium">{{ $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage() }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $attempt->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attempt->user->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-100 text-green-800 shadow-sm border border-green-200">
                                            {{ round($attempt->score) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attempt->created_at->format('d M Y, h:i A') }}
                                        <div class="text-xs text-gray-400">{{ $attempt->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa fa-inbox text-gray-300 text-4xl mb-3"></i>
                                            <p>No results found for this exam.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $attempts->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
