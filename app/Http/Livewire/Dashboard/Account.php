<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\File;

class Account extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $profile_image;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'profile_image' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function updatedProfileImage()
    {
        $this->validateOnly('profile_image');
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->profile_image) {
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $this->profile_image->extension();
            $path = $this->profile_image->storeAs('profiles', $filename, 'public');

            File::create([
                'module_name' => 'user',
                'module_id' => $user->id,
                'file_name' => $this->profile_image->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => 'image',
                'mime_type' => $this->profile_image->getMimeType(),
                'file_size' => $this->profile_image->getSize(),
            ]);

            $user->profile_image_path = $path;
        }

        // Update password if provided
        if (!empty($this->password)) {
            $user->password = \Illuminate\Support\Facades\Hash::make($this->password);
        }

        $user->save();

        session()->flash('success', 'Profile updated successfully.');
        $this->emit('profileUpdated');
    }

    // Deletion removed: account deletion handled elsewhere if needed

    public function render()
    {
        return view('livewire.dashboard.account');
    }
}
