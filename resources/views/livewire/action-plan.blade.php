<div>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">My Action Plan</h1>
        <form wire:submit.prevent="addTask" class="flex flex-col space-y-4" id="taskForm">
            <div class="flex space-x-2 items-center">
                <input type="text" wire:model.defer="title" id="titleInput" placeholder="Tulis Agendamu Disini..." class="border border-gray-300 rounded px-3 py-2 flex-1" />
                <button type="submit" id="submitButton" class="bg-green-500 text-white px-4 py-2 rounded disabled:opacity-50" disabled>
                    Tambah
                </button>
            </div>
            <div class="flex space-x-4">
                <label><input type="radio" name="priority" value="high" wire:model.defer="priority" class="priorityRadio"> High Priority</label>
                <label><input type="radio" name="priority" value="medium" wire:model.defer="priority" class="priorityRadio"> Medium Priority</label>
                <label><input type="radio" name="priority" value="low" wire:model.defer="priority" class="priorityRadio"> Low Priority</label>
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
                    <span>
                        {{ $task->title }}
                    </span>
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
                        Edit
                    </button>
                    <button x-on:click="if(confirm('Yakin hapus?')) { $wire.deleteTask({{ $task->id }}) }" class="text-red-600 hover:text-red-800">
                        Hapus
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
        radios.forEach(radio => {
            radio.addEventListener('change', checkForm);
        });
        checkForm();
        document.addEventListener('formReset', function() {
            titleInput.value = '';
            radios.forEach(radio => radio.checked = false);
            submitButton.disabled = true;
        });
    });
</script>