<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;

class Projects extends Component
{
    use WithPagination;

    public $showCreateModal = false;

    public $search = '';
    public $filterStatus = '';

    // Project form fields
    public $name;
    public $description;
    public $status = 'active';
    public $selectedCustomers = [];

    protected $queryString = ['search', 'filterStatus'];

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
            'selectedCustomers' => 'array',
        ];
    }

    public function createProject()
    {
        try {
            $this->validate();

            $project = Project::create([
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'created_by' => Auth::id(),
            ]);

            // Add customers
            if (!empty($this->selectedCustomers)) {
                $project->customers()->sync($this->selectedCustomers);
            }

            $this->dispatch('notify', message: 'Project created successfully!', type: 'success');
            $this->resetForm();
            $this->showCreateModal = false;
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to create project. ' . $e->getMessage(), type: 'error');
        }
    }



    protected function resetForm()
    {
        $this->name = null;
        $this->description = null;
        $this->status = 'active';
        $this->selectedCustomers = [];
    }

    public function render()
    {
        $user = Auth::user();
        $query = Project::query();

        // Filter by role
        if ($user->role === 'freelance') {
            // Freelancers see projects they created OR projects assigned to them
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('freelance_id', $user->id);
            });
        } elseif ($user->role === 'customer') {
            // Customers see projects they're associated with
            $query->whereHas('customers', function ($q) use ($user) {
                $q->where('customer_id', $user->id);
            });
        }
        // Admin sees all projects

        // Search filter
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $projects = $query->with('creator', 'freelance', 'customers')->paginate(12);

        return view('livewire.dashboard.projects', [
            'projects' => $projects,
            'customers' => User::where('role', 'customer')->get(),
        ]);
    }
}
