<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\TestComponent;

Route::get('/', function () {
    return view('layouts.app');
});


 Route::get('/test', TestComponent::class);
