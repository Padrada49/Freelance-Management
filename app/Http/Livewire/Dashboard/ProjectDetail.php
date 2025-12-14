<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;

    public $showEditModal = false;
    public $showEditCustomersModal = false;
    public $confirmingDeleteId = null;
    public $confirmingDeleteTaskId = null;

    // Project form fields
    public $name;
    public $description;
    public $status;
    public $selectedCustomers = [];

    // Task inline editing
    public $editingTaskId = null;
    public $addingNewTask = false;
    public $tasks = [];

    public function mount($id)
    {
        $this->projectId = $id;
        $this->loadProject();
    }

    public function loadProject()
    {
        $query = Project::with(['creator', 'customers', 'tasks.assignee']);

        $user = Auth::user();

        // Apply role-based filtering
        if ($user->role === 'freelance') {
            $query->where('created_by', $user->id);
        } elseif ($user->role === 'customer') {
            $query->whereHas('customers', function ($q) use ($user) {
                $q->where('customer_id', $user->id);
            });
        }

        $this->project = $query->findOrFail($this->projectId);
    }

    protected function projectRules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
            'selectedCustomers' => 'array',
        ];
    }

    protected function taskRules()
    {
        return [
            'taskTitle' => 'required|string|min:3|max:255',
            'taskDescription' => 'nullable|string',
            'taskStatus' => 'required|in:todo,in_progress,completed',
            'taskPriority' => 'required|in:low,medium,high',
            'taskAssignedTo' => 'nullable|exists:users,id',
            'taskDueDate' => 'nullable|date',
        ];
    }

    public function editProject()
    {
        // Check authorization
        if (Auth::user()->role === 'freelance' && $this->project->created_by !== Auth::id()) {
            $this->dispatch('notify', message: 'You can only edit your own projects.', type: 'warning');
            return;
        }

        $this->name = $this->project->name;
        $this->description = $this->project->description;
        $this->status = $this->project->status;
        $this->showEditModal = true;
    }

    public function editCustomers()
    {
        // Check authorization
        if (Auth::user()->role === 'freelance' && $this->project->created_by !== Auth::id()) {
            $this->dispatch('notify', message: 'You can only edit your own projects.', type: 'warning');
            return;
        }

        $this->selectedCustomers = $this->project->customers->pluck('id')->toArray();
        $this->showEditCustomersModal = true;
    }

    public function updateProject()
    {
        try {
            $this->validate([
                'name' => 'required|string|min:3|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:active,completed,on_hold',
            ]);

            // Check authorization
            if (Auth::user()->role === 'freelance' && $this->project->created_by !== Auth::id()) {
                $this->dispatch('notify', message: 'You can only edit your own projects.', type: 'warning');
                return;
            }

            $this->project->update([
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            $this->dispatch('notify', message: 'Project details updated successfully!', type: 'success');
            $this->showEditModal = false;
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to update project. ' . $e->getMessage(), type: 'error');
        }
    }

    public function updateCustomers()
    {
        try {
            $this->validate([
                'selectedCustomers' => 'array',
            ]);

            // Check authorization
            if (Auth::user()->role === 'freelance' && $this->project->created_by !== Auth::id()) {
                $this->dispatch('notify', message: 'You can only edit your own projects.', type: 'warning');
                return;
            }

            // Update customers
            $this->project->customers()->sync($this->selectedCustomers);

            $this->dispatch('notify', message: 'Customers updated successfully!', type: 'success');
            $this->showEditCustomersModal = false;
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to update customers. ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete()
    {
        $this->confirmingDeleteId = $this->project->id;
    }

    public function deleteProject()
    {
        try {
            // Check authorization
            if (Auth::user()->role === 'freelance' && $this->project->created_by !== Auth::id()) {
                $this->dispatch('notify', message: 'You can only delete your own projects.', type: 'warning');
                $this->confirmingDeleteId = null;
                return;
            }

            $this->project->delete();
            $this->dispatch('notify', message: 'Project deleted successfully!', type: 'success');
            return redirect()->route('dashboard.projects');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to delete project. ' . $e->getMessage(), type: 'error');
            $this->confirmingDeleteId = null;
        }
    }

    public function addNewTask()
    {
        $this->addingNewTask = true;
        $this->editingTaskId = null;
    }

    public function saveNewTask($index)
    {
        try {
            $task = $this->tasks[$index] ?? [];

            if (empty($task['title'])) {
                $this->dispatch('notify', message: 'Task title is required.', type: 'error');
                return;
            }

            Task::create([
                'project_id' => $this->project->id,
                'title' => $task['title'],
                'description' => $task['description'] ?? null,
                'status' => $task['status'] ?? 'todo',
                'priority' => $task['priority'] ?? 'medium',
                'assigned_to' => $task['assigned_to'] ?? null,
                'due_date' => $task['due_date'] ?? null,
            ]);

            $this->dispatch('notify', message: 'Task created successfully!', type: 'success');
            $this->addingNewTask = false;
            $this->tasks = [];
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to create task. ' . $e->getMessage(), type: 'error');
        }
    }

    public function cancelNewTask()
    {
        $this->addingNewTask = false;
        $this->tasks = [];
    }

    public function editTask($taskId)
    {
        $this->editingTaskId = $taskId;
        $this->addingNewTask = false;
    }

    public function updateTaskField($taskId, $field, $value)
    {
        try {
            $task = Task::findOrFail($taskId);
            $task->update([$field => $value]);
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to update task.', type: 'error');
        }
    }

    public function saveTask($taskId)
    {
        try {
            $this->editingTaskId = null;
            $this->dispatch('notify', message: 'Task updated successfully!', type: 'success');
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to save task.', type: 'error');
        }
    }

    public function cancelEdit()
    {
        $this->editingTaskId = null;
        $this->loadProject();
    }

    public function confirmDeleteTask($taskId)
    {
        $this->confirmingDeleteTaskId = $taskId;
    }

    public function deleteTask()
    {
        try {
            $task = Task::findOrFail($this->confirmingDeleteTaskId);
            $task->delete();

            $this->dispatch('notify', message: 'Task deleted successfully!', type: 'success');
            $this->confirmingDeleteTaskId = null;
            $this->loadProject();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to delete task. ' . $e->getMessage(), type: 'error');
            $this->confirmingDeleteTaskId = null;
        }
    }



    public function render()
    {
        return view('livewire.dashboard.project-detail', [
            'customers' => User::where('role', 'customer')->get(),
            'users' => User::whereIn('role', ['admin', 'freelance', 'customer'])->get(),
        ]);
    }
}
