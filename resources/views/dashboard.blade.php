@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12">
  <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
      </div>
      <div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="bg-black text-white px-4 py-2 rounded">Logout</button>
        </form>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 border rounded">
        <h3 class="font-semibold">Profile</h3>
        <p class="text-sm text-gray-600">Name: {{ auth()->user()->name }}</p>
        <p class="text-sm text-gray-600">Email: {{ auth()->user()->email }}</p>
      </div>

      <div class="p-4 border rounded">
        <h3 class="font-semibold">Role</h3>
        <p class="text-sm text-gray-600">{{ auth()->user()->role }}</p>
      </div>

      <div class="p-4 border rounded">
        <h3 class="font-semibold">Quick Actions</h3>
        <p class="text-sm text-gray-600">Placeholder for actions based on role.</p>
      </div>
    </div>
  </div>
</div>
@endsection
