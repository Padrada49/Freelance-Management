<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $profileImage;
    public $previewUrl;

    public function mount()
    {
        $user = auth()->user();
        if ($user->profile_image_path) {
            $this->previewUrl = $user->profile_image_url;
        }
    }

    public function uploadProfileImage()
    {
        try {
            $this->validate([
                'profileImage' => 'required|image|max:2048',
            ]);

            $user = auth()->user();

            // Store the image
            $path = $this->profileImage->store('profiles', 'public');

            // Save to database
            File::create([
                'module_name' => 'user',
                'module_id' => $user->id,
                'file_name' => $this->profileImage->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => 'image',
                'mime_type' => $this->profileImage->getMimeType(),
                'file_size' => $this->profileImage->getSize(),
            ]);

            // Update user profile_image_path
            $user->update(['profile_image_path' => $path]);

            $this->previewUrl = asset('storage/' . $path);
            $this->profileImage = null;

            $this->dispatch('notify', message: 'Profile image updated successfully!', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to upload image. ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.settings');
    }
}
