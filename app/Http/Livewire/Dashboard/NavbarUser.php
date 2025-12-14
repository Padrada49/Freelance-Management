<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class NavbarUser extends Component
{
    public $userName;
    public $userEmail;
    public $userImage;

    protected $listeners = ['profileUpdated' => 'refreshProfile'];

    public function mount()
    {
        $this->refreshProfile();
    }

    public function refreshProfile()
    {
        $user = auth()->user();
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userImage = $user->profile_image_url;
    }

    public function render()
    {
        return view('livewire.dashboard.navbar-user');
    }
}
