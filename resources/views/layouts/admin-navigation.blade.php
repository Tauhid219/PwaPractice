<div class="bg-gray-200 shadow-sm border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-2 py-2 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 rounded-md text-sm font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-800 hover:bg-gray-300 transition-all duration-200' }}">
               <i class="fa fa-tachometer-alt mr-2"></i>Dashboard
            </a>
            
            <a href="{{ route('admin.questions.index') }}" 
               class="px-4 py-2 rounded-md text-sm font-bold {{ request()->routeIs('admin.questions.*') ? 'bg-indigo-600 text-white' : 'text-gray-800 hover:bg-gray-300 transition-all duration-200' }}">
               <i class="fa fa-question-circle mr-2"></i>Questions
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 rounded-md text-sm font-bold {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-gray-800 hover:bg-gray-300 transition-all duration-200' }}">
               <i class="fa fa-users mr-2"></i>Users
            </a>
        </div>
    </div>
</div>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
