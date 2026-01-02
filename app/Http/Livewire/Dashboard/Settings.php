<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $profileImage;
    public $previewUrl;

    // Pricing settings - Lifetime prices per role
    public $freelance_price;
    public $customer_price;

    public function mount()
    {
        $user = auth()->user();
        if ($user->profile_image_path) {
            $this->previewUrl = $user->profile_image_url;
        }

        // Load pricing settings for admin
        if ($user->role === 'admin') {
            $this->freelance_price = Setting::get('freelance_price', 2990);
            $this->customer_price = Setting::get('customer_price', 1990);
        }
    }

    public function savePricing()
    {
        $this->validate([
            'freelance_price' => 'required|numeric|min:0',
            'customer_price' => 'required|numeric|min:0',
        ]);

        Setting::set('freelance_price', $this->freelance_price);
        Setting::set('customer_price', $this->customer_price);

        $this->dispatch('notify', message: 'Pricing settings saved successfully!', type: 'success');
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
