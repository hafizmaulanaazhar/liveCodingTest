<div x-data="{ showDetail: false }">
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg">
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
                <button type="button" class="text-black-600 font-bold italic flex items-center text-sm" @click="showDetail = !showDetail">
                    <span>Detail</span>
                    <svg x-show="!showDetail" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="showDetail" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
            </div>

            <div x-show="showDetail" x-transition class="space-y-3 mt-3">
                <div class="flex flex-col md:flex-row md:space-x-3 space-y-3 md:space-y-0">
                    <div class="mb-4">
                        <label for="due_dateInput" class="block text-gray-700 font-medium mb-1">Due Date</label>
                        <input type="date" wire:model.defer="due_date" id="due_dateInput" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-500" />
                    </div>
                    <div class="mb-4 flex items-center space-x-2">
                        <label for="categoryInput" class="text-gray-700 font-medium">Kategori</label>
                        <select wire:model.defer="category" id="categoryInput" class="border border-gray-300 rounded px-3 py-2 flex-1 focus:ring focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            <option value="main">Main Job</option>
                            <option value="side">Side Project</option>
                        </select>
                        <button type="button" wire:click="$set('showCategoryForm', true)" class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                            Tambah
                        </button>
                    </div>
                    @if($showCategoryForm)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                            <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>
                            <input type="text" wire:model.defer="newCategory" placeholder="Nama Kategori" class="border border-gray-300 rounded px-3 py-2 w-full mb-4 focus:ring focus:ring-blue-200 focus:border-blue-500" />
                            <div class="flex justify-end space-x-2">
                                <button wire:click="saveCategory" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
                                <button wire:click="$set('showCategoryForm', false)" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">Batal</button>
                            </div>
                        </div>
                    </div>
                    @endif
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

    <div class="max-w-xl mx-auto mt-6">
        @if($tasks->isEmpty())
        <p class="text-center text-gray-500">Belum ada agenda, silahkan tambah terlebih dahulu...</p>
        @else
        <ul class="space-y-3">
            @foreach($tasks as $task)
            <li wire:key="task-{{ $task->id }}" class="flex justify-between items-center p-3 bg-white rounded shadow">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" wire:click="markDone({{ $task->id }})">
                    @if($editId === $task->id)
                    <input type="text" wire:model="editTitle" wire:keydown.enter="saveEdit" class="border rounded px-2 py-1">
                    @else
                    <span>{{ $task->title }}</span>
                    @endif

                    <span class="px-2 py-0.5 rounded text-white text-sm
                                @if($task->priority === 'high') bg-red-500
                                @elseif($task->priority === 'medium') bg-blue-500
                                @else bg-gray-500 @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
                <div class="flex space-x-2 items-center">
                    <button wire:click="startEdit({{ $task->id }})" class="text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                        </svg>
                    </button>
                    <button x-on:click="if(confirm('Yakin hapus?')) { $wire.deleteTask({{ $task->id }}) }" class="text-red-600 hover:text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1h5v2H4V3h5zM6 7h12l-1 12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                        </svg>
                    </button>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('titleInput');
        const radios = document.querySelectorAll('.priorityRadio');
        const submitButton = document.getElementById('submitButton');

        function checkForm() {
            const titleFilled = titleInput.value.trim() !== '';
            const prioritySelected = Array.from(radios).some(r => r.checked);
            submitButton.disabled = !(titleFilled && prioritySelected);
        }

        titleInput.addEventListener('input', checkForm);
        radios.forEach(radio => radio.addEventListener('change', checkForm));
        checkForm();

        document.addEventListener('formReset', function() {
            titleInput.value = '';
            radios.forEach(radio => radio.checked = false);
            submitButton.disabled = true;
        });
    });
</script>