<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Register extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'customer';
    public $profile_image = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:customer,freelance,admin',
        'profile_image' => 'nullable|image|max:2048',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        // Handle profile image upload
        if ($this->profile_image) {
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $this->profile_image->extension();
            $path = $this->profile_image->storeAs('profiles', $filename, 'public');

            // Save to files table
            File::create([
                'module_name' => 'user',
                'module_id' => $user->id,
                'file_name' => $this->profile_image->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => 'image',
                'mime_type' => $this->profile_image->getMimeType(),
                'file_size' => $this->profile_image->getSize(),
            ]);

            // Update user profile_image_path
            $user->update(['profile_image_path' => $path]);
        }

        Auth::attempt(['email' => $this->email, 'password' => $this->password]);
        session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
