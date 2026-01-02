<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\File;
use App\Models\Setting;
use App\Models\PaymentProof;
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
    public $payment_slip = null;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:customer,freelance',
        'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        'payment_slip' => 'required|image|mimes:jpeg,jpg,png,gif,pdf|max:2048',
    ];

    protected $messages = [
        'name.required' => 'Please enter your full name',
        'name.min' => 'Name must be at least 3 characters',
        'name.max' => 'Name cannot exceed 255 characters',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
        'password.confirmed' => 'Passwords do not match',
        'role.required' => 'Please select an account type',
        'profile_image.image' => 'Profile picture must be an image',
        'profile_image.mimes' => 'Profile picture must be JPEG, JPG, PNG or GIF',
        'profile_image.max' => 'Profile picture must not exceed 2MB',
        'payment_slip.required' => 'Payment slip is required',
        'payment_slip.image' => 'Payment slip must be an image',
        'payment_slip.mimes' => 'Payment slip must be JPEG, JPG, PNG, GIF or PDF',
        'payment_slip.max' => 'Payment slip must not exceed 2MB',
    ];

    public function getAmountProperty()
    {
        return Setting::get($this->role . '_price', 0);
    }

    public function updatedRole()
    {
        // Force re-render when role changes
    }

    public function register()
    {
        try {
            // Validate all inputs
            $validatedData = $this->validate();

            \Log::info('=== Registration Started ===', [
                'email' => $this->email,
                'role' => $this->role,
                'has_profile_image' => !is_null($this->profile_image),
                'has_payment_slip' => !is_null($this->payment_slip),
            ]);

            // Check if amount is available
            if ($this->amount <= 0) {
                $this->dispatch('show-notification', [
                    'type' => 'error',
                    'message' => 'Pricing not configured. Please contact administrator.'
                ]);
                return;
            }

            // Create user (not approved yet)
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'is_approved' => false,
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user account');
            }

            \Log::info('User created', ['user_id' => $user->id]);

            // Handle profile image upload
            if ($this->profile_image) {
                try {
                    $filename = 'profile_' . $user->id . '_' . time() . '.' . $this->profile_image->getClientOriginalExtension();
                    $path = $this->profile_image->storeAs('profiles', $filename, 'public');

                    if (!$path) {
                        throw new \Exception('Failed to store profile image');
                    }

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
                    $user->save();

                    \Log::info('Profile image uploaded', ['path' => $path]);
                } catch (\Exception $e) {
                    \Log::error('Profile image upload failed', ['error' => $e->getMessage()]);
                    // Continue even if profile image fails (it's optional)
                }
            }

            // Handle payment slip upload (REQUIRED)
            if (!$this->payment_slip) {
                throw new \Exception('Payment slip is required');
            }

            try {
                $slipFilename = 'payment_slip_' . $user->id . '_' . time() . '.' . $this->payment_slip->getClientOriginalExtension();
                $slipPath = $this->payment_slip->storeAs('payment_slips', $slipFilename, 'public');

                if (!$slipPath) {
                    throw new \Exception('Failed to store payment slip');
                }

                \Log::info('Payment slip uploaded', [
                    'filename' => $slipFilename,
                    'path' => $slipPath,
                    'size' => $this->payment_slip->getSize(),
                ]);

                $paymentProof = PaymentProof::create([
                    'user_id' => $user->id,
                    'subscription_type' => 'lifetime',
                    'amount' => $this->amount,
                    'proof_file' => $slipPath,
                    'status' => 'pending',
                ]);

                if (!$paymentProof) {
                    throw new \Exception('Failed to save payment proof');
                }

                \Log::info('PaymentProof created', ['id' => $paymentProof->id]);
            } catch (\Exception $e) {
                \Log::error('Payment slip upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Delete user if payment slip upload fails
                $user->delete();

                throw new \Exception('Failed to upload payment slip. Please try again.');
            }

            \Log::info('=== Registration Completed Successfully ===');

            // Show success notification
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Registration successful! Your account is pending approval. You will be notified via email.'
            ]);

            // Redirect to login after short delay
            session()->flash('success', 'Registration successful! Please wait for admin approval.');
            return redirect()->route('login');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);

            // Show validation errors in notification
            $errorMessages = collect($e->errors())->flatten()->implode(', ');
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Validation Error: ' . $errorMessages
            ]);

            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Registration failed: ' . $e->getMessage()
            ]);

            session()->flash('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
