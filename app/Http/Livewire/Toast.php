<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Toast extends Component
{
    public $message = '';
    public $type = 'success'; // success, error, warning, info
    public $visible = false;
    public $duration = 5000;

    protected $listeners = ['notify' => 'show'];

    public function show($message, $type = 'success', $duration = 5000)
    {
        $this->message = $message;
        $this->type = $type;
        $this->duration = $duration;
        $this->visible = true;

        $this->dispatch('closeToastAfter', delay: $duration);
    }

    public function close()
    {
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
