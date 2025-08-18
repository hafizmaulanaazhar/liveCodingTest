<div>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg mt-4">
        <h1 class="text-2xl font-bold italic text-center text-gray-800 mb-1">My Action Plan</h1>
        <h2 class="text-1xl italic text-center text-gray-900 mb-4">Keep yourself organize and be productive</h2>
        <form wire:submit.prevent="addTask" class="flex flex-col space-y-4" id="taskForm">
            <div class="flex space-x-2 items-center">
                <input type="text" wire:model.defer="title" id="titleInput" placeholder="Tulis Agendamu Disini..." class="border border-gray-300 rounded px-3 py-2 flex-1" />
                <button type="submit" id="submitButton" class="bg-green-500 text-white px-4 py-2 rounded disabled:opacity-50" disabled>
                    Tambah
                </button>
            </div>
            <div class="flex space-x-4 italic text-center">
                <label><input type="radio" name="priority" value="high" wire:model.defer="priority" class="priorityRadio"> High Priority</label>
                <label><input type="radio" name="priority" value="medium" wire:model.defer="priority" class="priorityRadio"> Medium Priority</label>
                <label><input type="radio" name="priority" value="low" wire:model.defer="priority" class="priorityRadio"> Low Priority</label>
            </div>
            <div>
                <button type="button" class="text-black-600 font-bold italic flex items-center text-sm" id="toggleDetailBtn">
                    <span>Detail</span>
                    <svg id="iconDown" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg id="iconUp" style="display:none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
            </div>
            <div id="detailSection" style="display:none" class="space-y-3 mt-3">
                <div class="flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0 items-end">
                    <div class="flex-1">
                        <label for="due_dateInput" class="block text-gray-700 font-medium mb-1">Due Date</label>
                        <input type="date" wire:model.defer="due_date" id="due_dateInput" value="{{ now()->toDateString() }}" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-500" />
                    </div>
                    <div class="flex-1">
                        <label for="categoryInput" class="flex items-center text-gray-700 font-medium mb-1">
                            Kategori
                            <button type="button" id="openCategoryModal" class="ml-2 text-green-500 hover:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </label>
                        <select wire:model.defer="category" id="categoryInput" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="detailAgendaInput" class="block font-medium text-gray-700 mb-1">
                        Detail Agenda
                    </label>
                    <textarea wire:model.defer="detail_agenda" id="detailAgendaInput" rows="5" placeholder="Tulis detail agenda di sini..." class="w-full border border-gray-300 rounded px-4 py-3 text-lg focus:ring focus:ring-blue-200 focus:border-blue-500 resize-none"></textarea>
                </div>
            </div>
        </form>
    </div>

    <div id="categoryModal" style="display:none" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-lg p-6 w-96 z-50 border border-gray-200">
        <h2 class="text-1xl italic text-center font-bold mb-4">Tambah Kategori</h2>
        <input type="text" wire:model.defer="newCategory" placeholder="Nama Kategori" class="border border-gray-300 rounded px-3 py-2 w-full mb-4 focus:ring focus:ring-blue-200 focus:border-blue-500" />
        <div class="flex justify-end space-x-2">
            <div class="w-full flex justify-center mt-4 space-x-2">
                <button wire:click="saveCategory" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Simpan
                </button>
                <button type="button" id="closeCategoryModal" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-xl mx-auto mt-6">
        @if($this->tasks->isEmpty())
        <p class="text-center text-gray-500">Belum ada agenda, silahkan tambah terlebih dahulu...</p>
        @else
        <ul class="space-y-3">
            @foreach($this->tasks as $task)
            <li wire:key="task-{{ $task->id }}" class="relative p-4 bg-white rounded-lg shadow border cursor-pointer hover:shadow-md transition" wire:click="showTaskDetail({{ $task->id }})">
                <div class="absolute top-3 right-3 flex space-x-2">
                    <button wire:click.stop="startEdit({{ $task->id }})" class="hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                        </svg>
                    </button>
                    <button wire:click="deleteTask({{ $task->id }})" onclick="return confirm('Yakin hapus?')" class="hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1h5v2H4V3h5zM6 7h12l-1 12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                        </svg>
                    </button>
                </div>

                <span class="inline-block px-3 py-1 rounded-full text-white text-sm font-bold
                    @if($task->priority === 'high') bg-red-500
                    @elseif($task->priority === 'medium') bg-blue-500
                    @else bg-gray-500 @endif">
                    {{ ucfirst($task->priority) }}
                </span>

                <div class="flex items-center justify-between text-gray-500 text-sm mt-2">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d F Y') }}
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 7l9-4 9 4-9 4-9-4z" />
                        </svg>
                        {{ $task->category }}
                    </div>
                </div>

                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center">
                        <input type="checkbox" wire:click.stop="markDone({{ $task->id }})" class="mr-2">
                        @if($editId === $task->id)
                        <input type="text" wire:model="editTitle" wire:keydown.enter="saveEdit" class="border rounded px-2 py-1">
                        @else
                        <span class="{{ $task->done ? 'line-through text-gray-400' : '' }}">
                            {{ $task->title }}
                        </span>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endif

        @if($showModal && $selectedTask)
        <div class="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-[500px]">
                <h2 class="text-2xl italic font-bold mb-6 text-center">Detail Agenda</h2>
                <input type="text" wire:model.defer="title" placeholder="Masukkan judul agenda" class="w-full border border-gray-300 rounded px-4 py-2 mb-4">
                <textarea wire:model.defer="detail_agenda" rows="4" placeholder="Tulis detail agenda..." class="w-full border border-gray-300 rounded px-4 py-2 mb-4 resize-none"></textarea>
                <div class="flex items-center gap-4 mb-4">
                    <label><input type="radio" name="priority" value="high" wire:model.defer="priority" class="priorityRadio"> High Priority</label>
                    <label><input type="radio" name="priority" value="medium" wire:model.defer="priority" class="priorityRadio"> Medium Priority</label>
                    <label><input type="radio" name="priority" value="low" wire:model.defer="priority" class="priorityRadio"> Low Priority</label>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Due Date</label>
                        <input type="date" wire:model.defer="due_date" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kategori</label>
                        <select wire:model.defer="category" id="categoryInput" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-center gap-4">
                    <button wire:click="saveDetail" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">Simpan</button>
                    <button wire:click="closeModal" class="px-6 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div>
        @if($this->tasks->hasMorePages()) <div class="mt-4 flex justify-center">
            <button wire:click="loadMorePage" wire:loading.attr="disabled" class="text-gray-700 flex items-center gap-2 hover:underline">
                Tampilkan Lagi
                <svg wire:loading wire:target="loadMore" class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                </svg>
                <svg id="iconDown" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                </svg>
            </button>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('titleInput');
        const radios = document.querySelectorAll('.priorityRadio');
        const submitButton = document.getElementById('submitButton');
        const category = document.getElementById('categoryInput');
        const due_date = document.getElementById('due_dateInput');
        const detail_agenda = document.getElementById('detailAgendaInput');
        const toggleDetailBtn = document.getElementById('toggleDetailBtn');
        const detailSection = document.getElementById('detailSection');
        const iconDown = document.getElementById('iconDown');
        const iconUp = document.getElementById('iconUp');
        const categoryModal = document.getElementById('categoryModal');
        const openCategoryModal = document.getElementById('openCategoryModal');
        const closeCategoryModal = document.getElementById('closeCategoryModal');

        function checkForm() {
            const titleFilled = titleInput.value.trim() !== '';
            const prioritySelected = Array.from(radios).some(r => r.checked);
            const dueDateFilled = due_dateInput.value.trim() !== '';
            const categoryFilled = categoryInput.value.trim() !== '';
            const detailFilled = detailAgendaInput.value.trim() !== '';

            if (detailSection.style.display === 'block') {
                submitButton.disabled = !(titleFilled && prioritySelected && dueDateFilled && categoryFilled && detailFilled);
            } else {
                submitButton.disabled = !(titleFilled && prioritySelected);
            }
        }

        titleInput.addEventListener('input', checkForm);
        radios.forEach(radio => radio.addEventListener('change', checkForm));
        due_dateInput.addEventListener('input', checkForm);
        categoryInput.addEventListener('input', checkForm);
        detailAgendaInput.addEventListener('input', checkForm);
        checkForm();

        toggleDetailBtn.addEventListener('click', function() {
            const isVisible = detailSection.style.display === 'block';
            detailSection.style.display = isVisible ? 'none' : 'block';
            iconDown.style.display = isVisible ? 'inline' : 'none';
            iconUp.style.display = isVisible ? 'none' : 'inline';
            checkForm();
        });

        openCategoryModal.addEventListener('click', function() {
            categoryModal.style.display = 'block';
        });
        closeCategoryModal.addEventListener('click', function() {
            categoryModal.style.display = 'none';
        });

        document.addEventListener('formReset', function() {
            titleInput.value = '';
            categoryInput.value = '';
            due_date.value = '';
            detail_agenda.value = '';
            radios.forEach(radio => radio.checked = false);
            submitButton.disabled = true;
        });
    });
</script>