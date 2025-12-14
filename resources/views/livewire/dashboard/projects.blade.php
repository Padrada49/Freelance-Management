<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold">Projects</h3>
            <p class="text-sm text-slate-600">Manage your projects and tasks.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="px-3 py-2 bg-black text-white rounded">
            + New Project
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Projects</label>
                <input type="text" wire:model.live="search" placeholder="Search..." class="w-full border px-3 py-2 rounded text-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="filterStatus" class="w-full border px-3 py-2 rounded text-sm">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="on_hold">On Hold</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="flex items-end">
                <div class="text-sm text-slate-600">
                    Showing <strong>{{ $projects->count() }}</strong> of <strong>{{ $projects->total() }}</strong> projects
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @forelse($projects as $project)
            <a wire:navigate href="/dashboard/projects/{{ $project->id }}" class="bg-white rounded shadow hover:shadow-lg transition p-4 cursor-pointer block">
                <!-- Project Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-900">{{ $project->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            by
                            @if($project->freelance)
                                <span class="font-medium text-blue-600">{{ $project->freelance->name }}</span>
                            @else
                                {{ $project->creator->name }}
                            @endif
                        </p>
                    </div>
                    <span class="px-2 py-1 bg-slate-100 rounded text-xs font-medium
                        @if($project->status === 'active') bg-green-100 text-green-800
                        @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>

                <!-- Description -->
                @if($project->description)
                    <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $project->description }}</p>
                @endif

                <!-- Customers -->
                @if($project->customers->count() > 0)
                    <div class="mb-3">
                        <p class="text-xs font-medium text-slate-600 mb-2">Customers:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->customers as $customer)
                                <span class="px-2 py-1 bg-blue-50 border border-blue-200 rounded text-xs">
                                    {{ $customer->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Task Count -->
                <div class="py-3 border-t border-slate-200">
                    <p class="text-sm text-slate-700">
                        <strong>{{ $project->tasks->count() }}</strong> tasks
                        <span class="text-xs text-slate-500">({{ $project->tasks->where('status', 'completed')->count() }} completed)</span>
                    </p>
                </div>
            </a>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p>No projects found. Create one to get started!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>

    <!-- Create Project Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="$set('showCreateModal', false)"></div>
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Create Project</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-600 hover:text-slate-800">&times;</button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="createProject" class="space-y-4">
                        <!-- Project Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" wire:model.defer="name" class="mt-1 block w-full border px-3 py-2 rounded" />
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model.defer="description" rows="3" class="mt-1 block w-full border px-3 py-2 rounded"></textarea>
                            @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model.defer="status" class="mt-1 block w-full border px-3 py-2 rounded">
                                <option value="active">Active</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                            </select>
                            @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Customers -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customers (Optional)</label>
                            <div class="mt-2 space-y-2 border rounded p-3 max-h-48 overflow-y-auto">
                                @foreach($customers as $customer)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded">
                                        <input type="checkbox" wire:model.defer="selectedCustomers" value="{{ $customer->id }}" class="rounded" />
                                        <img src="{{ $customer->profile_image_url }}" alt="{{ $customer->name }}" class="w-6 h-6 rounded-full" />
                                        <span class="text-sm">{{ $customer->name }} ({{ $customer->email }})</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedCustomers') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="flex-1 px-4 py-2 bg-black text-white rounded">Create</button>
                            <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 px-4 py-2 border rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
