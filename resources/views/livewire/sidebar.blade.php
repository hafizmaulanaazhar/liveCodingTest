<div class="bg-white shadow rounded-lg p-4 space-y-4">
    <!-- Filter Hari Ini -->
    <button wire:click="setFilter('today')" class="flex justify-between items-center p-2 rounded transition 
               {{ $activeFilter === 'today' ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
        <span>Hari Ini</span>
        <span class="px-2 rounded {{ $activeFilter === 'today' ? 'bg-white text-blue-500' : 'bg-gray-200' }}">
            {{ $this->todayCount }}
        </span>
    </button>

    <!-- Filter 7 Hari ke Depan -->
    <button wire:click="setFilter('next7')" class="flex justify-between items-center p-2 rounded transition 
               {{ $activeFilter === 'next7' ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
        <span>7 Hari ke Depan</span>
        <span class="px-2 rounded {{ $activeFilter === 'next7' ? 'bg-white text-blue-500' : 'bg-gray-200' }}">
            {{ $this->next7DaysCount }}
        </span>
    </button>

    <!-- Kategori -->
    <div class="border-t pt-2">
        <h3 class="text-sm font-semibold text-gray-500">Kategori</h3>
        <div class="flex flex-col gap-2 mt-2">
            @foreach ($this->categories as $cat)
            <button wire:click="setFilter('category:{{ $cat->category }}')" class="flex justify-between items-center p-2 rounded transition
                           {{ $activeFilter === 'category:'.$cat->category ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                <span>{{ $cat->category ?? 'Tanpa Kategori' }}</span>
                <span class="px-2 rounded {{ $activeFilter === 'category:'.$cat->category ? 'bg-white text-blue-500' : 'bg-gray-200' }}">
                    {{ $cat->total }}
                </span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Selesai -->
    <div class="border-t pt-2">
        <button wire:click="setFilter('done')" class="flex justify-between items-center p-2 rounded transition 
                   {{ $activeFilter === 'done' ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
            <span>Selesai</span>
            <span class="px-2 rounded {{ $activeFilter === 'done' ? 'bg-white text-blue-500' : 'bg-gray-200' }}">
                {{ $this->doneCount }}
            </span>
        </button>
    </div>
</div>