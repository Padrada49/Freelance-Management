<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\PaymentProof;

class Approve extends Component
{
    public $selectedUser = null;
    public $selectedProof = null;
    public $showUserDetail = false;
    public $adminNote = '';
    public $filterStatus = 'pending';

    public function viewUser($userId)
    {
        $this->selectedUser = User::with(['paymentProofs' => function($q) {
            $q->latest();
        }])->find($userId);

        $this->selectedProof = $this->selectedUser->paymentProofs->first();
        $this->showUserDetail = true;
        $this->adminNote = '';
    }

    public function closeUserDetail()
    {
        $this->showUserDetail = false;
        $this->selectedUser = null;
        $this->selectedProof = null;
        $this->adminNote = '';
    }

    public function approveUser()
    {
        if (!$this->selectedUser) {
            return;
        }

        // Approve user
        $this->selectedUser->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Approve payment proof if exists
        if ($this->selectedProof) {
            $this->selectedProof->update([
                'status' => 'approved',
                'admin_note' => $this->adminNote,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }

        $this->dispatch('notify', message: 'User approved successfully!', type: 'success');
        $this->closeUserDetail();
    }

    public function rejectUser()
    {
        if (!$this->selectedUser) {
            return;
        }

        // Reject payment proof if exists
        if ($this->selectedProof) {
            $this->selectedProof->update([
                'status' => 'rejected',
                'admin_note' => $this->adminNote,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }

        // Keep user unapproved
        $this->dispatch('notify', message: 'User rejected.', type: 'info');
        $this->closeUserDetail();
    }

    public function render()
    {
        // Get pending users (with or without payment proofs)
        $pendingQuery = User::with(['paymentProofs' => function($q) {
            $q->latest()->limit(1);
        }])
        ->where('is_approved', false);

        // Apply payment proof filter only if user has payment proofs
        if ($this->filterStatus !== 'all') {
            $pendingQuery->where(function($q) {
                $q->whereHas('paymentProofs', function($query) {
                    $query->where('status', $this->filterStatus);
                })
                ->orWhereDoesntHave('paymentProofs'); // Include users without payment proofs
            });
        }

        $pendingUsers = $pendingQuery->latest()->get();

        // Get approved users
        $approvedUsers = User::with(['paymentProofs' => function($q) {
            $q->where('status', 'approved')->latest()->limit(1);
        }])
        ->where('is_approved', true)
        ->latest()
        ->take(10)
        ->get();

        return view('livewire.dashboard.approve', [
            'pendingUsers' => $pendingUsers,
            'approvedUsers' => $approvedUsers,
        ]);
    }
}
