<div wire:poll.1s class="shadow-lg rounded-xl p-4 w-64 space-y-6">
    <div class="space-y-2">
        <div class="flex flex-col gap-2 mt-2">
            <button wire:click="setFilter('today')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'today' ? 'bg-blue-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">Hari Ini</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full {{ $activeFilter === 'today' ? 'bg-white text-blue-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $this->todayCount }}
                </span>
            </button>
            <button wire:click="setFilter('next7')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'next7' ? 'bg-blue-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">7 Hari ke Depan</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full {{ $activeFilter === 'next7' ? 'bg-white text-blue-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $this->next7DaysCount }}
                </span>
            </button>
        </div>
    </div>

    <div class="border-t border-gray-300 pt-4 space-y-2">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Kategori</h3>
        <div class="flex flex-col gap-2 mt-2">
            <button wire:click="setFilter('category:Main Job')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'category:Main Job' ? 'bg-blue-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">Main Job</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full 
                {{ $activeFilter === 'category:Main Job' ? 'bg-white text-blue-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $this->mainJob }}
                </span>
            </button>
            <button wire:click="setFilter('category:Side Job')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'category:Side Job' ? 'bg-blue-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">Side Job</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full 
                {{ $activeFilter === 'category:Side Job' ? 'bg-white text-blue-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $this->sideJob }}
                </span>
            </button>
            @foreach ($this->categories as $cat)
            @if (!in_array($cat->category, ['Main Job', 'Side Job']) && !empty($cat->category))
            <button wire:click="setFilter('category:{{ $cat->category }}')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'category:'.$cat->category ? 'bg-blue-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">{{ $cat->category }}</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full 
                {{ $activeFilter === 'category:'.$cat->category ? 'bg-white text-blue-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $cat->total }}
                </span>
            </button>
            @endif
            @endforeach
        </div>
    </div>

    <div class="border-t border-gray-300 pt-4">
        <div class="flex flex-col gap-2 mt-2">
            <button wire:click="setFilter('done')" class="flex justify-between items-center p-3 rounded-lg transition-all duration-200
            {{ $activeFilter === 'done' ? 'bg-green-500 text-white shadow-md' : 'hover:bg-gray-200' }}">
                <span class="font-medium">Selesai</span>
                <span class="text-left px-3 py-1 text-sm font-semibold rounded-full {{ $activeFilter === 'done' ? 'bg-white text-green-500' : 'bg-gray-300 text-gray-700' }}">
                    {{ $this->doneCount }}
                </span>
            </button>
        </div>
    </div>
</div>