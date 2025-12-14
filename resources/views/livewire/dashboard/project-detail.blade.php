<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-3">
                <a wire:navigate href="/dashboard/projects" class="text-slate-600 hover:text-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h3 class="text-lg font-semibold">{{ $project->name }}</h3>
                    <p class="text-sm text-slate-600">Created by {{ $project->creator->name }}</p>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                <button wire:click="confirmDelete" class="px-3 py-2 border border-red-500 text-red-600 rounded hover:bg-red-50">
                    Delete Project
                </button>
            @endif
        </div>
    </div>

    <!-- Project Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Project Info -->
        <div class="lg:col-span-2 bg-white rounded shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold">Project Information</h4>
                @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                    <button wire:click="editProject" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Details
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-600">Status</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 rounded text-sm font-medium
                            @if($project->status === 'active') bg-green-100 text-green-800
                            @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                </div>

                @if($project->description)
                    <div>
                        <label class="text-sm font-medium text-slate-600">Description</label>
                        <p class="mt-1 text-sm text-slate-700">{{ $project->description }}</p>
                    </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-slate-600">Created</label>
                    <p class="mt-1 text-sm text-slate-700">{{ $project->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Customers & Stats -->
        <div class="space-y-6">
            <!-- Customers -->
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold">Customers ({{ $project->customers->count() }})</h4>
                    @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                        <button wire:click="editCustomers" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Manage
                        </button>
                    @endif
                </div>
                @if($project->customers->count() > 0)
                    <div class="space-y-2">
                        @foreach($project->customers as $customer)
                            <div class="flex items-center gap-3 p-2 bg-slate-50 rounded">
                                <img src="{{ $customer->profile_image_url }}" alt="{{ $customer->name }}" class="w-8 h-8 rounded-full" />
                                <div>
                                    <p class="text-sm font-medium">{{ $customer->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No customers assigned</p>
                @endif
            </div>

            <!-- Task Stats -->
            <div class="bg-white rounded shadow p-6">
                <h4 class="font-semibold mb-4">Task Statistics</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Total Tasks</span>
                        <span class="font-semibold">{{ $project->tasks->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Completed</span>
                        <span class="font-semibold text-green-600">{{ $project->tasks->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">In Progress</span>
                        <span class="font-semibold text-blue-600">{{ $project->tasks->where('status', 'in_progress')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Todo</span>
                        <span class="font-semibold text-slate-600">{{ $project->tasks->where('status', 'todo')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="bg-white">
        <div class="flex items-center justify-between p-4 border-b">
            <h4 class="font-semibold">Tasks</h4>
            @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                <button wire:click="addNewTask" class="px-3 py-2 bg-black text-white rounded text-sm">
                    + Add Task
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Task</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase w-32">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase w-28">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase w-40">Assigned To</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase w-32">Due Date</th>
                        @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-600 uppercase w-24">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <!-- New Task Row -->
                    @if($addingNewTask)
                        <tr class="bg-blue-50">
                            <td class="px-4 py-3">
                                <input type="text" wire:model.defer="tasks.0.title" placeholder="Task title..." class="w-full border px-2 py-1 rounded text-sm" />
                                <textarea wire:model.defer="tasks.0.description" placeholder="Description (optional)..." rows="2" class="w-full border px-2 py-1 rounded text-sm mt-1"></textarea>
                            </td>
                            <td class="px-4 py-3">
                                <select wire:model.defer="tasks.0.status" class="w-full border px-2 py-1 rounded text-sm">
                                    <option value="todo">Todo</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <select wire:model.defer="tasks.0.priority" class="w-full border px-2 py-1 rounded text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <select wire:model.defer="tasks.0.assigned_to" class="w-full border px-2 py-1 rounded text-sm">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" wire:model.defer="tasks.0.due_date" class="w-full border px-2 py-1 rounded text-sm" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 justify-center">
                                    <button wire:click="saveNewTask(0)" class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Save</button>
                                    <button wire:click="cancelNewTask" class="px-2 py-1 bg-slate-400 text-white rounded text-xs hover:bg-slate-500">Cancel</button>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <!-- Existing Tasks -->
                    @forelse($project->tasks as $task)
                        @if($editingTaskId === $task->id)
                            <!-- Edit Mode -->
                            <tr class="bg-yellow-50">
                                <td class="px-4 py-3">
                                    <input type="text" value="{{ $task->title }}" wire:change="updateTaskField({{ $task->id }}, 'title', $event.target.value)" class="w-full border px-2 py-1 rounded text-sm font-medium" />
                                    <textarea wire:change="updateTaskField({{ $task->id }}, 'description', $event.target.value)" rows="2" class="w-full border px-2 py-1 rounded text-sm mt-1">{{ $task->description }}</textarea>
                                </td>
                                <td class="px-4 py-3">
                                    <select wire:change="updateTaskField({{ $task->id }}, 'status', $event.target.value)" class="w-full border px-2 py-1 rounded text-sm">
                                        <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>Todo</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select wire:change="updateTaskField({{ $task->id }}, 'priority', $event.target.value)" class="w-full border px-2 py-1 rounded text-sm">
                                        <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select wire:change="updateTaskField({{ $task->id }}, 'assigned_to', $event.target.value)" class="w-full border px-2 py-1 rounded text-sm">
                                        <option value="">Unassigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" wire:change="updateTaskField({{ $task->id }}, 'due_date', $event.target.value)" class="w-full border px-2 py-1 rounded text-sm" />
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1 justify-center">
                                        <button wire:click="saveTask({{ $task->id }})" class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Done</button>
                                        <button wire:click="cancelEdit" class="px-2 py-1 bg-slate-400 text-white rounded text-xs hover:bg-slate-500">Cancel</button>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <!-- View Mode -->
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-sm">{{ $task->title }}</p>
                                    @if($task->description)
                                        <p class="text-xs text-slate-600 mt-1">{{ $task->description }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="updateTaskField({{ $task->id }}, 'status', '{{ $task->status === 'todo' ? 'in_progress' : ($task->status === 'in_progress' ? 'completed' : 'todo') }}')"
                                        class="px-2 py-1 rounded text-xs font-medium cursor-pointer hover:opacity-80 transition
                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($task->priority === 'high') bg-red-100 text-red-800
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($task->assignee)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $task->assignee->profile_image_url }}" alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full" />
                                            <span class="text-sm">{{ $task->assignee->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($task->due_date)
                                        <span class="text-sm text-slate-700">{{ $task->due_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-xs text-slate-400">No date</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin' || $project->created_by === auth()->id())
                                    <td class="px-4 py-3">
                                        <div class="flex gap-1 justify-center">
                                            <button wire:click="editTask({{ $task->id }})" class="text-blue-600 hover:text-blue-800 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDeleteTask({{ $task->id }})" class="text-red-600 hover:text-red-800 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @empty
                        @if(!$addingNewTask)
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>No tasks yet. Create one to get started!</p>
                                </td>
                            </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Project Details Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="$set('showEditModal', false)"></div>
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Edit Project Details</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-slate-600 hover:text-slate-800">&times;</button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="updateProject" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" wire:model.defer="name" class="mt-1 block w-full border px-3 py-2 rounded" />
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model.defer="description" rows="3" class="mt-1 block w-full border px-3 py-2 rounded"></textarea>
                            @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model.defer="status" class="mt-1 block w-full border px-3 py-2 rounded">
                                <option value="active">Active</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                            </select>
                            @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="flex-1 px-4 py-2 bg-black text-white rounded">Update</button>
                            <button type="button" wire:click="$set('showEditModal', false)" class="flex-1 px-4 py-2 border rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Customers Modal -->
    @if($showEditCustomersModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="$set('showEditCustomersModal', false)"></div>
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Manage Customers</h3>
                    <button wire:click="$set('showEditCustomersModal', false)" class="text-slate-600 hover:text-slate-800">&times;</button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="updateCustomers" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Customers</label>
                            <div class="space-y-2 border rounded p-3 max-h-64 overflow-y-auto">
                                @forelse($customers as $customer)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded">
                                        <input type="checkbox" wire:model.defer="selectedCustomers" value="{{ $customer->id }}" class="rounded" />
                                        <img src="{{ $customer->profile_image_url }}" alt="{{ $customer->name }}" class="w-8 h-8 rounded-full" />
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">{{ $customer->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-sm text-slate-500 text-center py-4">No customers available</p>
                                @endforelse
                            </div>
                            @error('selectedCustomers') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="flex-1 px-4 py-2 bg-black text-white rounded">Save Changes</button>
                            <button type="button" wire:click="$set('showEditCustomersModal', false)" class="flex-1 px-4 py-2 border rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Project Confirmation -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="$set('confirmingDeleteId', null)"></div>
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
                <div class="p-4">
                    <p class="text-sm text-red-800">Are you sure you want to delete this project? This will also delete all tasks. This cannot be undone.</p>
                    <div class="mt-3 flex gap-2">
                        <button wire:click="deleteProject" class="px-3 py-2 bg-red-600 text-white rounded">Yes, delete</button>
                        <button wire:click="$set('confirmingDeleteId', null)" class="px-3 py-2 border rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Task Confirmation -->
    @if($confirmingDeleteTaskId)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="$set('confirmingDeleteTaskId', null)"></div>
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
                <div class="p-4">
                    <p class="text-sm text-red-800">Are you sure you want to delete this task? This cannot be undone.</p>
                    <div class="mt-3 flex gap-2">
                        <button wire:click="deleteTask" class="px-3 py-2 bg-red-600 text-white rounded">Yes, delete</button>
                        <button wire:click="$set('confirmingDeleteTaskId', null)" class="px-3 py-2 border rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
