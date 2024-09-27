<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Labour;

class LabourHistory extends Component
{
    public $labour;

    public function mount($labour)
    {
        $this->labour = Labour::with('labourWorks')->find($labour)->toArray();
        
    }

    public function render()
    {
        return view('livewire.labour-history', [
            'labour' => $this->labour
        ]);
    }
}
