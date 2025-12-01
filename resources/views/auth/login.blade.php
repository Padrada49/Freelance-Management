@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    @livewire('auth.login')
  </div>
</div>
@endsection
