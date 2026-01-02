<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function create()
    {
        // Only admin can access
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Only admin can access
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|in:admin,freelance,customer',
                'profile_image' => 'nullable|image|max:2048',
            ]);

            Log::info('Creating user with data:', $validated);

            // Create user with auto-approval
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            Log::info('User created successfully:', ['user_id' => $user->id]);

            // Handle profile image if uploaded
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->extension();
                $path = $file->storeAs('profiles', $filename, 'public');

                File::create([
                    'module_name' => 'user',
                    'module_id' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => 'image',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                $user->profile_image_path = $path;
                $user->save();

                Log::info('Profile image uploaded:', ['path' => $path]);
            }

            return redirect()->route('admin.users.create')
                ->with('success', 'User "' . $user->name . '" created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('User creation failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }
}
